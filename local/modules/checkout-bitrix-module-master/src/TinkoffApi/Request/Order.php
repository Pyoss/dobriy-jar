<?php

namespace TinkoffCheckout\TinkoffApi\Request;

use Bitrix\Main\Application;
use Bitrix\Main\Event;
use TinkoffCheckout\Entities\BlockProperty;
use TinkoffCheckout\Entities\Delivery;
use TinkoffCheckout\Entities\Product;
use TinkoffCheckout\Entities\Vat;
use TinkoffCheckout\Helpers\StringConvertor;
use TinkoffCheckout\Logger\FileLogger;
use TinkoffCheckout\Settings\Helpers\SettingsFields;
use TinkoffCheckout\TinkoffApi\Client;

class Order
{

    private $order;
    private $basket;


    private $status = false;
    private $error = 'Неизвестная ошибка';
    private $url = '';

    public function __construct($order, $basket)
    {
        $this->order  = $order;
        $this->basket = $basket;
    }


    public function request()
    {
        require_once __DIR__ . '/../../../include.php';

        $logger = new FileLogger();

        $body = $this->body();

        // Шаг 3. Запрос к API Tinkoff
        $apiClient = new Client();

        // Событие создания заказа по API
        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_INVOICE_CREATE_VIA_API, [&$body]);
        $event->send();

        $response = $apiClient->createOrder($body);

        // Событие после формирования заказа и отправки его по API
        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_AFTER_INVOICE_CREATE, [
            $body,
            $response
        ]);
        $event->send();

        $status = isset($response['url']) && $response['url'];
        $url    = $status ? $response['url'] : null;
        $error  = !$status ? $response : [];

        $logger->info('Завершение создания заказа', [
            'status' => $status,
            'url'    => $url,
            'error'  => $error
        ]);

        $this->status = $status;
        $this->url    = $url;
        $this->error  = $error;
    }


    private function body()
    {
        require_once __DIR__ . '/../../../include.php';

        $order  = $this->order;
        $basket = $this->basket;

        $taxation     = SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_TAXATION) ?: 'none';
        $deliveryType = SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_DELIVERY_TYPE) ?: 'disable';

        $redirectURL = $this->getRedirectURL();
        $coupons     = $this->getCouponsResult($order, $basket);

        $deliveryPrice = 0;
        switch ($deliveryType) {
            case Delivery::DELIVERY_TYPE_MERCHANT:
                $deliveryPrice = Delivery::getPrice(Delivery::DELIVERY_TYPE_MERCHANT) * 100;
                $deliveryTax   = Vat::getVatAssoc(Delivery::getVatRate(Delivery::DELIVERY_TYPE_MERCHANT));
                break;
            case Delivery::DELIVERY_TYPE_METASHIP:
                $deliveryTax = Vat::getVatAssoc(Delivery::getVatRate(Delivery::DELIVERY_TYPE_METASHIP));
                break;
            default:
                $deliveryTax = 'none';
        }


        $apiQuery = [
            'shopId'         => SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_SHOP_ID),
            'orderId'        => strval($order->getId()),
            'description'    => 'Заказ с CMS Bitrix',
            'taxation'       => $taxation,
            'successPageUrl' => $redirectURL,
        ];
        if (count($coupons) > 0) {
            $apiQuery['discountInfo'] = $coupons;
        }

        // Формирование списка товаров для запроса
        $orderItems       = [];
        $itemsAmount      = 0;
        $itemsWeight      = 0;
        $itemsHeight      = 0;
        $itemsWidth       = 0;
        $itemsLength      = 0;
        $declaredValueSum = 0;

        $basketItems = $basket->getOrderableItems();
        foreach ($basketItems as $basketItem) {
            $quantity = $basketItem->getQuantity();
            $price    = $basketItem->getField('PRICE');
            $itemTax  = $this->getProductTax($basketItem);
            $amount   = $price * $quantity * 100;

            $sku        = null;
            $properties = $basketItem->getPropertyCollection();
            foreach ($properties as $property) {
                $name = $property->getField('NAME');
                $code = $property->getField('CODE');

                if (mb_strpos(mb_strtolower($name), 'артикул') !== false || $code == 'ARTNUMBER') {
                    $sku = $property->getField('VALUE');
                }
            }

            $itemsAmount += $amount;

            $item = [
                'name'     => $basketItem->getField('NAME'),
                'quantity' => $quantity,
                'amount'   => $amount,
                'price'    => $price * 100,
                'tax'      => $itemTax,
            ];

            if (!$sku && $deliveryType == Delivery::DELIVERY_TYPE_METASHIP) {
                $sku = 'article';
            }

            if ($sku) {
                $item['article'] = $sku;
            }

            if ($deliveryType == Delivery::DELIVERY_TYPE_METASHIP) {
                // Габариты товара
                $weight = Product::getWeight($basketItem) * $quantity;
                $height = Product::getHeight($basketItem) * $quantity;
                $width  = Product::getWidth($basketItem);
                $length = Product::getLength($basketItem);

                $itemsWeight += $weight;
                $itemsHeight += $height;

                if ($width > $itemsWidth) {
                    $itemsWidth = $width;
                }
                if ($length > $itemsLength) {
                    $itemsLength = $length;
                }

                $item['weight'] = $weight;

                // Объявленная стоимость
                $productID             = $basketItem->getField('PRODUCT_ID');
                $declaredValue         = BlockProperty::getProductProperty(
                    $productID,
                    BlockProperty::TYPE_DECLARED_VALUE
                );
                $declaredValue         = StringConvertor::toFloat($declaredValue) * 100;
                $declaredValue         = $declaredValue ?: 100;
                $item['declaredValue'] = $declaredValue;

                $declaredValueSum += $declaredValue;
            }

            $event = new Event(
                TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_BEFORE_ITEM_ADD_TO_INVOICE,
                [&$item]
            );
            $event->send();

            $orderItems[] = $item;
        }
        $apiQuery['orderItems'] = $orderItems;

        // Настройка доставки
        if ($deliveryType === Delivery::DELIVERY_TYPE_MERCHANT) {
            $apiQuery['deliveryCondition'] = [
                'tax'   => $deliveryTax,
                'price' => $deliveryPrice,
            ];
        } elseif ($deliveryType == Delivery::DELIVERY_TYPE_METASHIP) {
            $shopID       = SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_SHOP_ID);
            $waterhouseID = SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_WAREHOUSE_ID);
            $types        = SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_TYPES);

            $types = $types && json_decode($types) ? json_decode($types, true) : [];

            $apiQuery['deliveryCondition'] = [
                'tax' => $deliveryTax,
            ];

            $apiQuery['deliveryCondition']['shippingParameters'] = [
                'metashipShopId'      => $shopID,
                'metashipWarehouseId' => $waterhouseID,
                'types'               => $types,
                'weight'              => $itemsWeight,
                'height'              => $itemsHeight,
                'width'               => $itemsWidth,
                'length'              => $itemsLength,
                'declaredValue'       => $declaredValueSum,
            ];
        }

        $apiQuery['itemsAmount'] = $itemsAmount;

        return $apiQuery;
    }

    private function getRedirectURL()
    {
        $url = SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_REDIRECT_URL);
        if ($url) {
            return $url;
        }

        $context    = Application::getInstance()->getContext();
        $server     = $context->getServer();
        $defaultURL = $server->getServerName();
        if (!$defaultURL) {
            $defaultURL = $server->getHttpHost();
        }

        $defaultURL = str_replace([':443', ':80'], '', $defaultURL);

        $isHTTPS = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

        return ($isHTTPS ? 'https://' : 'http://') . $defaultURL;
    }

    private function getProductTax($basketItem)
    {
        return Vat::getVatAssoc($basketItem->getField('VAT_RATE'));
    }


    private function getCouponsResult($order, $basket)
    {
        $productQuantity = [];
        foreach ($basket as $basketItem) {
            $id       = $basketItem->getId();
            $quantity = $basketItem->getQuantity();

            $productQuantity[$id] = $quantity;
        }

        $orderDiscounts = $order->getDiscount();
        $orderDiscounts = $orderDiscounts->getApplyResult(true);

        $couponsList   = $orderDiscounts['COUPON_LIST'];
        $couponsBasket = $orderDiscounts['RESULT']['BASKET'];
        $couponsPrices = $orderDiscounts['PRICES']['BASKET'];

        $coupons = [];
        foreach ($couponsList as $code => $data) {
            $coupons[$data['ORDER_DISCOUNT_ID']] = $code;
        }

        $prices = [];
        foreach ($couponsPrices as $productID => $data) {
            $quantity = isset($productQuantity[$productID]) ? $productQuantity[$productID] : 1;

            $prices[$productID] = $data['DISCOUNT'] * $quantity;
        }

        $result = [];
        foreach ($couponsBasket as $productID => $discounts) {
            foreach ($discounts as $discount) {
                $discountID = $discount['DISCOUNT_ID'];
                $code       = isset($coupons[$discountID]) ? $coupons[$discountID] : null;
                if (!$code) {
                    continue;
                }

                $result[$code] = isset($result[$code]) ? $result[$code] : 0;
                $price         = isset($prices[$productID]) ? $prices[$productID] : 0;

                $result[$code] += $price;
            }
        }

        $formattedResult = [];
        foreach ($result as $code => $amount) {
            $formattedResult = [
                'promoCodeId'    => $code,
                'discountAmount' => $amount * 100
            ];
        }

        return $formattedResult;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getUrl()
    {
        return $this->url;
    }
}