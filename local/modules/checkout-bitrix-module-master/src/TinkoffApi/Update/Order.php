<?php

namespace TinkoffCheckout\TinkoffApi\Update;

use Bitrix\Main\Engine\JsonPayload;
use TinkoffCheckout\Entities\OrderStatuses;
use TinkoffCheckout\Logger\FileLogger;
use TinkoffCheckout\Settings\Helpers\SettingsFields;
use TinkoffCheckout\TinkoffApi\Client;

class Order
{
    private $request = [];

    private $orderID = null;
    private $orderStatus = 'N';
    private $isPaid = 'N';
    private $isDelivered = 'N';
    private $isEnableDelivery = 'N';
    private $isMarked = 'N';

    private $isHandleOrder = false;
    private $orderData = [];

    const DELIVERY_ENABLE = 'Y';
    const DELIVERY_DISABLE = 'N';

    const PAID = 'Y';
    const NON_PAID = 'N';

    const IS_HAS_PROBLEM = 'Y';
    const IS_HAS_NOT_PROBLEM = 'N';

    const STATUS_COMPLETED = 'F';
    const STATUS_COMPLETED_WAIT_DELIVERY = 'P';
    const STATUS_COMPLETED_WAIT_PAYMENT = 'N';

    const IS_DELIVERED = 'Y';
    const IS_NOT_DELIVERED = 'N';


    public function __construct()
    {
        require_once __DIR__ . '/../../../include.php';

        $payload = new JsonPayload();
        $request = $payload->getData();

        $log = new FileLogger();
        $log->info('Обновление информации по заказу от API', [
            'request(php input)' => $payload->getRaw()
        ]);

        $this->setRequest($request);
        $this->setOrderID($request['orderId']);

        $status = $request['status'];

        // Таблица b_sale_status_lang
        switch ($status) {
            case 'PAYMENT_SUCCESS':
                $orderStatus      = $this->isDeliveryEnable()
                    ? self::STATUS_COMPLETED_WAIT_DELIVERY
                    : self::STATUS_COMPLETED;
                $isDelivered      = $this->isDeliveryEnable()
                    ? self::IS_NOT_DELIVERED
                    : self::IS_DELIVERED;
                $isDeliveryEnable = self::DELIVERY_DISABLE;
                $isPaid           = self::PAID;
                $isMarked         = self::IS_HAS_NOT_PROBLEM;

                $this->handleOrder($request['orderId']);
                break;
            case 'PAYMENT_AWAITING':
                $orderStatus      = self::STATUS_COMPLETED_WAIT_PAYMENT;
                $isDelivered      = self::IS_NOT_DELIVERED;
                $isDeliveryEnable = self::DELIVERY_DISABLE;
                $isPaid           = self::NON_PAID;
                $isMarked         = self::IS_HAS_NOT_PROBLEM;

                $this->handleOrder($request['orderId']);
                break;
            case 'DELIVERY_EXT_PROCESSING':
                $isDelivered      = self::IS_NOT_DELIVERED;
                $isDeliveryEnable = self::DELIVERY_DISABLE;
                $orderStatus      = OrderStatuses::ID_METASHIP_PROCESSING;
                $isPaid           = self::PAID;
                $isMarked         = self::IS_HAS_NOT_PROBLEM;
                break;
            case 'DELIVERY_EXT_PROCESSING_SUCCESS':
                $isDelivered      = self::IS_NOT_DELIVERED;
                $isDeliveryEnable = self::DELIVERY_ENABLE;
                $orderStatus      = OrderStatuses::ID_METASHIP_PROCESSING_SUCCESS;
                $isPaid           = self::PAID;
                $isMarked         = self::IS_HAS_NOT_PROBLEM;

                $this->handleOrder($request['orderId']);
                break;
            case 'DELIVERY_EXT_PROCESSING_FAILURE':
                $isDelivered      = self::IS_NOT_DELIVERED;
                $isDeliveryEnable = self::DELIVERY_DISABLE;
                $orderStatus      = OrderStatuses::ID_METASHIP_PROCESSING_FAILURE;
                $isPaid           = self::PAID;
                $isMarked         = self::IS_HAS_PROBLEM;
                break;
            case 'DELIVERY_EXT_PROCESSING_NOT_STARTED':
                $isDelivered      = self::IS_NOT_DELIVERED;
                $isDeliveryEnable = self::DELIVERY_DISABLE;
                $orderStatus      = OrderStatuses::ID_METASHIP_PROCESSING_NOT_STARTED;
                $isPaid           = self::PAID;
                $isMarked         = self::IS_HAS_PROBLEM;
                break;
//            case 'PAYMENT_FAILURE':
//                $orderStatus      = 'N';
//                $isPaid           = "N";
//                $isDelivered      = 'N';
//                $isDeliveryEnable = 'N';
//                $isMarked         = 'Y';
//                break;
            default:
                $orderStatus      = self::STATUS_COMPLETED_WAIT_PAYMENT;
                $isDelivered      = self::IS_NOT_DELIVERED;
                $isPaid           = self::NON_PAID;
                $isDeliveryEnable = self::DELIVERY_DISABLE;
                $isMarked         = self::IS_HAS_NOT_PROBLEM;
        }

        $this->setOrderStatus($orderStatus);
        $this->setIsPaid($isPaid);
        $this->setIsDelivered($isDelivered);
        $this->setIsEnableDelivery($isDeliveryEnable);
        $this->setIsMarked($isMarked);
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function getOrderID()
    {
        return $this->orderID;
    }

    public function setOrderID($orderID)
    {
        $this->orderID = $orderID;
    }

    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    public function getIsPaid()
    {
        return $this->isPaid;
    }

    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;
    }

    public function isHandleOrder()
    {
        return $this->isHandleOrder;
    }

    public function setIsHandleOrder($isHandleOrder)
    {
        $this->isHandleOrder = $isHandleOrder;
    }

    public function getOrderData()
    {
        return $this->orderData;
    }

    public function setOrderData($orderData)
    {
        $this->orderData = $orderData;
    }

    public function getIsDelivered()
    {
        return $this->isDelivered;
    }

    public function setIsDelivered($isDelivered)
    {
        $this->isDelivered = $isDelivered;
    }

    public function getIsEnableDelivery()
    {
        return $this->isEnableDelivery;
    }

    public function setIsEnableDelivery($isEnableDelivery)
    {
        $this->isEnableDelivery = $isEnableDelivery;
    }

    public function getIsMarked()
    {
        return $this->isMarked;
    }

    public function setIsMarked($isMarked)
    {
        $this->isMarked = $isMarked;
    }

    private function handleOrder($orderID)
    {
        $apiClient = new Client();

        $response = $apiClient->getOrder($orderID);
        if (isset($response[Client::ERROR_FIELD_NAME])) {
            return;
        }

        $this->setIsHandleOrder(true);
        $this->setOrderData($response['order']);
    }

    private function isDeliveryEnable()
    {
        return SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_DELIVERY_TYPE) !== 'disable';
    }
}