<?php

namespace TinkoffCheckout\Entities;

use Bitrix\Catalog\VatTable;
use Bitrix\Main\SystemException;
use Bitrix\Sale\Delivery\Services\Manager;
use Bitrix\Main\Loader;
use Bitrix\Main\Event;

class Delivery
{
    const DELIVERY_TYPE_MERCHANT = 'by_merchant';
    const DELIVERY_TYPE_METASHIP = 'by_service';
    const DELIVERY_TYPES = [
        self::DELIVERY_TYPE_MERCHANT,
        self::DELIVERY_TYPE_METASHIP,
    ];

    const DELIVERY_MERCHANT_CODE = 'tinkoff_checkout_by_merchant';
    const DELIVERY_METASHIP_CODE = 'tinkoff_checkout_by_service';

    const DELIVERY_MERCHANT_NAME = 'Тинькофф Корзина. Сбор и передача адреса';
    const DELIVERY_METASHIP_NAME = 'Тинькофф Корзина. Интеграция со службами доставки';

    public static function getID($type = null)
    {
        require_once __DIR__ . '/../../include.php';

        // Подключение зависимостей
        Loader::includeModule("sale");

        if (!$type) {
            return null;
        }

        $code = self::getCodeByType($type);
        if (!$code) {
            return null;
        }

        try {
            $entity = Manager::getObjectByCode($code);

            $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_GET_DELIVERY, [
                &$entity,
                $type,
            ]);
            $event->send();

            return $entity->getId();
        } catch (SystemException $exception) {
        }

        return null;
    }

    public static function getPrice($type)
    {
        $id = Delivery::getID($type);
        if (!$id) {
            return 0;
        }

        $shipment = Manager::getObjectById($id);
        $values   = $shipment->getConfigValues();

        $values = isset($values['MAIN']) && $values['MAIN'] ? $values['MAIN'] : [];
        $price  = isset($values['PRICE']) && $values['PRICE'] ? $values['PRICE'] : 0;

        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_GET_DELIVERY_PRICE, [
            &$price,
            $type
        ]);
        $event->send();

        return $price;
    }

    public static function getVatRate($type)
    {
        $id = Delivery::getID($type);
        if (!$id) {
            return 0;
        }

        $shipment = Manager::getObjectById($id);
        $vatID    = $shipment->getVatId();
        if (!$vatID) {
            return 0;
        }

        $vat = VatTable::getById($vatID);
        $vat = $vat->fetch();


        $rate = $vat && isset($vat['RATE']) ? $vat['RATE'] : 0;

        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_GET_DELIVERY_VAT_RATE, [
            &$rate,
            $type
        ]);
        $event->send();

        return $rate / 100;
    }

    public static function add($type)
    {
        global $APPLICATION;

        // Подключение зависимостей
        Loader::includeModule("sale");

        $code = self::getCodeByType($type);
        if (!$code) {
            return null;
        }

        $deliveryID = Delivery::getID($type);
        if ($deliveryID) {
            return true;
        }

        $delivery   = Manager::add([
            'NAME'                => self::getNameByType($type),
            'ACTIVE'              => 'Y',
            'DESCRIPTION'         => 'Доставка созданная через модуль Tinkoff Checkout',
            'CLASS_NAME'          => '\Bitrix\Sale\Delivery\Services\Configurable',
            'ALLOW_EDIT_SHIPMENT' => 'Y',
        ]);
        $deliveryID = $delivery->getId();
        Manager::update($deliveryID, [
            'CODE' => $code
        ]);

        return true;
    }

    public static function remove($type)
    {
        // Подключение зависимостей
        Loader::includeModule("sale");

        $deliveryID = Delivery::getID($type);
        if (!$deliveryID) {
            return false;
        }

        Manager::delete($deliveryID);

        return true;
    }

    private static function getCodeByType($type = null)
    {
        if (!$type) {
            return null;
        }

        $code = $type == self::DELIVERY_TYPE_MERCHANT
            ? self::DELIVERY_MERCHANT_CODE
            : self::DELIVERY_METASHIP_CODE;

        return $code;
    }

    private static function getNameByType($type = null)
    {
        if (!$type) {
            return 'Tinkoff Checkout';
        }

        $name = $type == self::DELIVERY_TYPE_MERCHANT
            ? self::DELIVERY_MERCHANT_NAME
            : self::DELIVERY_METASHIP_NAME;

        return $name;
    }
}