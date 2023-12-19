<?php

namespace Tinkoff\Checkout\Controller;

use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\Response\Json;
use Bitrix\Main\Engine\Response\Redirect;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Delivery\Services\Manager as DeliveryManager;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Internals\PaySystemActionTable;
use Bitrix\Sale\Order as BitrixOrder;
use Bitrix\Sale\PaySystem\Manager as PaymentManager;
use CCatalogProduct;
use CModule;
use COption;
use CUser;
use TinkoffCheckout\Entities\Delivery;
use TinkoffCheckout\Entities\PaymentMethod;
use TinkoffCheckout\Logger\FileLogger;
use TinkoffCheckout\Settings\Helpers\SettingsFields;
use TinkoffCheckout\TinkoffApi\Request\Order as OrderRequest;
use TinkoffCheckout\TinkoffApi\Update\Order as OrderUpdate;

class Checkout extends Controller
{
    public function configureActions()
    {
        return [
            'getCheckoutRedirect' => [
                '-prefilters' => [
                    Csrf::class,
                    Authentication::class
                ]
            ],
            'updateStatus'        => [
                '-prefilters' => [
                    Csrf::class,
                    Authentication::class
                ]
            ],
            'updateOrderStatus'   => [
                '-prefilters' => [
                    Csrf::class,
                    Authentication::class
                ]
            ],
            'test'                => [
                '-prefilters' => [
                    Csrf::class,
                    Authentication::class
                ]
            ],
        ];
    }

    public function testAction()
    {
        return new Json([
            'test' => 'test',
        ]);
    }

    public function getCheckoutRedirectAction()
    {
        global $USER;

        require_once __DIR__ . '/../../include.php';

        $logger = new FileLogger();

        // Подключение зависимостей
        Loader::includeModule("sale");
        Loader::includeModule("catalog");
        Loader::includeModule("iblock");

        $deliveryType = SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_DELIVERY_TYPE) ?: 'disable';

        // Событие до создания заказа
        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_BEFORE_INVOICE_CREATE);
        $event->send();

        // Шаг 1. Создание заказа
        $basket = Basket::loadItemsForFUser(
            Fuser::getId(),
            Context::getCurrent()->getSite()
        );

        if (!$basket || $basket->count() == 0) {
            return $this->actionRedirectResponse(false, null, 'Необходимо заполнить корзину');
        }

        $siteId       = Context::getCurrent()->getSite();
        $currencyCode = CurrencyManager::getBaseCurrency();
        $order        = BitrixOrder::create($siteId, $USER->isAuthorized() ? $USER->GetID() : 1);

        $order->setPersonTypeId(1);
        $order->setBasket($basket);
        $order->setField('CURRENCY', $currencyCode);

        // Таблица b_sale_delivery_srv
        $shipmentCollection = $order->getShipmentCollection();
        switch ($deliveryType) {
            case 'by_merchant':
                $deliveryID = Delivery::getID(Delivery::DELIVERY_TYPE_MERCHANT);

                $shipment = DeliveryManager::getObjectById($deliveryID);
                $shipment = $shipmentCollection->createItem($shipment);
                break;
            case 'by_service':
                $deliveryID = Delivery::getID(Delivery::DELIVERY_TYPE_METASHIP);

                $shipment = DeliveryManager::getObjectById($deliveryID);
                $shipment = $shipmentCollection->createItem($shipment);
                break;
            default:
                $shipment = $shipmentCollection->createItem(DeliveryManager::getObjectById(1));
        }

        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        $basket->refresh();
        foreach ($basket as $basketItem) {
            $item = $shipmentItemCollection->createItem($basketItem);
            $item->setQuantity($basketItem->getQuantity());
        }

        $paymentMethod = new PaymentMethod();
        $paymentID     = $paymentMethod->getID();
        if (!$paymentID) {
            $logger->error('Ошибка при создании заказа: способ оплаты не найден');
            return $this->actionRedirectResponse(false, null, 'Ошибка получения способа оплаты');
        }

        $paymentCollection = $order->getPaymentCollection();
        $payment           = $paymentCollection->createItem(PaymentManager::getObjectById($paymentID));

        $payment->setField("SUM", $order->getPrice());
        $payment->setField("CURRENCY", $order->getCurrency());

        $order->doFinalAction(true);
        $result = $order->save();
        if (!$result->isSuccess()) {
            $logger->error('Ошибка при создании заказа: не удалось сохранить заказ');
            return $this->actionRedirectResponse(false, null, 'Ошибка сохранения заказа');
        }

        $orderRequest = new OrderRequest($order, $basket);
        $orderRequest->request();

        $status = $orderRequest->getStatus();
        $url    = $orderRequest->getUrl();
        $error  = $orderRequest->getError();
        return $this->actionRedirectResponse($status, $url, $error);
    }

    public function updateOrderStatusAction()
    {
        require_once __DIR__ . '/../../include.php';

        // Подключение зависимостей
        Loader::includeModule("sale");
        Loader::includeModule("catalog");

        $update = new OrderUpdate();

        // Событие до обновления заказа
        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_BEFORE_ORDER_UPDATE, [&$update]);
        $event->send();

        if (!$update->getOrderID()) {
            return new Json([
                'status' => 'error',
            ]);
        }

        $order = BitrixOrder::load($update->getOrderID());

        // Событие обновления заказа
        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_ORDER_UPDATE, [
            &$order,
            &$update,
        ]);
        $event->send();

        $paymentCollection  = $order->getPaymentCollection();
        $shipmentCollection = $order->getShipmentCollection();

        foreach ($paymentCollection as $payment) {
            $payment->setPaid($update->getIsPaid());
        }

        foreach ($shipmentCollection as $shipment) {
            if ($shipment->isSystem()) {
                continue;
            }

            $shipment->setField('DEDUCTED', $update->getIsDelivered());
            $shipment->setField('ALLOW_DELIVERY', $update->getIsEnableDelivery());
        }

        $userDescription = '';
        if ($update->isHandleOrder()) {
            //b_sale_order_props
            $properties = $order->getPropertyCollection();
            $orderData  = $update->getOrderData();

            $email     = null;
            $firstName = null;
            $lastName  = null;
            $phone     = null;
            $address   = null;

            $orderRecipient = isset($orderData['recipient']) && $orderData['recipient'] ? $orderData['recipient'] : null;
            $orderUser      = isset($orderData['user']) && $orderData['user'] ? $orderData['user'] : null;
            $orderDelivery  = isset($orderData['delivery']) && $orderData['delivery'] ? $orderData['delivery'] : null;
            if ($orderUser) {
                $email     = $orderUser['email'];
                $firstName = $orderUser['firstName'];
                $lastName  = $orderUser['lastName'];
                $phone     = $orderUser['phone'];

                $userDescription = 'Email: ' . $email . "\n";
                $userDescription .= 'Имя: ' . $firstName . "\n";
                $userDescription .= 'Фамилия: ' . $lastName . "\n";
                $userDescription .= 'Телефон: ' . $phone . "\n";
            }
            if ($orderRecipient) {
                $email     = $orderRecipient['email'];
                $firstName = $orderRecipient['firstName'];
                $lastName  = $orderRecipient['lastName'];
                $phone     = $orderRecipient['phone'];
            }

            if ($orderDelivery) {
                $address = $orderDelivery['address'];
            }

            foreach ($properties as $property) {
                $code = $property->getField('CODE');
                switch ($code) {
                    case 'EMAIL':
                        $property->setField('VALUE', $email);
                        break;
                    case 'FIO':
                        $property->setField('VALUE', trim($firstName . ' ' . $lastName));
                        break;
                    case 'PHONE':
                        $property->setField('VALUE', $phone);
                        break;
                    case 'ADDRESS':
                        $property->setField('VALUE', $address);
                        break;
                }
            }
        }

        $orderDescriptionPrefix = 'Tinkoff Checkout, Заказчик:';
        $orderDescription       = $order->getField('USER_DESCRIPTION');
        if (strpos($orderDescription, $orderDescriptionPrefix) === false) {
            $orderDescription = $orderDescriptionPrefix . "\n" . $userDescription . "\n\n" . $orderDescription;
            $order->setField('USER_DESCRIPTION', $orderDescription);
        }
        $order->setField('STATUS_ID', $update->getOrderStatus());
        $order->setField('MARKED', $update->getIsMarked());

        $order->doFinalAction(true);
        $order->save();

        // Событие после обновления заказа
        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_AFTER_ORDER_UPDATE, [&$order]);
        $event->send();

        return new Json([
            'status' => 'success',
        ]);
    }

    public function updateStatusAction()
    {
        return $this->updateOrderStatusAction();
    }

    private function actionRedirectResponse($status = true, $url = null, $error = '')
    {
        $response                = [];
        $response['status']      = $status ? 'success' : 'error';
        $response['data']['url'] = $url;
        if ($error) {
            $response['error']         = $error;
            $response['data']['error'] = $response;
        }

        return new Json($response);
    }
}