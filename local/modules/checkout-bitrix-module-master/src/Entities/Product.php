<?php

namespace TinkoffCheckout\Entities;

use TinkoffCheckout\Helpers\StringConvertor;
use TinkoffCheckout\Settings\Helpers\SettingsFields;
use Bitrix\Main\Event;
use Bitrix\Sale\BasketItemBase;

class Product
{
    public static function getWeight($product)
    {
        require_once __DIR__ . '/../../include.php';

        $weight = 0;
        if (is_subclass_of($product, BasketItemBase::class)) {
            $weight = self::getBasketItemWeight($product);
        }

        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_GET_PRODUCT_WEIGHT, [
            &$weight,
            $product
        ]);
        $event->send();

        return StringConvertor::toFloat($weight);
    }

    public static function getHeight($product)
    {
        require_once __DIR__ . '/../../include.php';

        $height = 0;
        if (is_subclass_of($product, BasketItemBase::class)) {
            $height = self::getBasketItemHeight($product);
        }

        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_GET_PRODUCT_HEIGHT, [
            &$height,
            $product
        ]);
        $event->send();

        return StringConvertor::toFloat($height);
    }

    public static function getWidth($product)
    {
        require_once __DIR__ . '/../../include.php';

        $width = 0;
        if (is_subclass_of($product, BasketItemBase::class)) {
            $width = self::getBasketItemWidth($product);
        }

        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_GET_PRODUCT_WIDTH, [
            &$width,
            $product
        ]);
        $event->send();

        return StringConvertor::toFloat($width);
    }

    public static function getLength($product)
    {
        require_once __DIR__ . '/../../include.php';

        $length = 0;
        if (is_subclass_of($product, BasketItemBase::class)) {
            $length = self::getBasketItemLength($product);
        }

        $event = new Event(TINKOFF_CHECKOUT_MODULE_ID, TINKOFF_CHECKOUT_ACTION_GET_PRODUCT_LENGTH, [
            &$length,
            $product
        ]);
        $event->send();

        return StringConvertor::toFloat($length);
    }

    private static function getBasketItemWeight($basketItem)
    {
        require_once __DIR__ . '/../../include.php';

        $weight = $basketItem->getWeight();
        $weight = StringConvertor::toFloat($weight) * 1000;
        if ($weight) {
            return $weight;
        }

        return SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_DELIVERY_WEIGHT);
    }

    private static function getBasketItemHeight($basketItem)
    {
        require_once __DIR__ . '/../../include.php';

        $height = $basketItem->getField('HEIGHT');
        $height = StringConvertor::toFloat($height) * 10;
        if ($height) {
            return $height;
        }

        return SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_DELIVERY_HEIGHT);
    }

    private static function getBasketItemWidth($basketItem)
    {
        require_once __DIR__ . '/../../include.php';

        $width = $basketItem->getField('WIDTH');
        $width = StringConvertor::toFloat($width) * 10;
        if ($width) {
            return $width;
        }

        return SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_DELIVERY_WIDTH);
    }

    private static function getBasketItemLength($basketItem)
    {
        require_once __DIR__ . '/../../include.php';

        $length = $basketItem->getField('LENGTH');
        $length = StringConvertor::toFloat($length) * 10;
        if ($length) {
            return $length;
        }

        return SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_DELIVERY_LENGTH);
    }
}