<?php

namespace TinkoffCheckout\Entities;

use Bitrix\Iblock\ElementPropertyTable;
use Bitrix\Main\Loader;
use CIBlock;
use CIBlockProperty;

class BlockProperty
{
    const TYPE_DECLARED_VALUE = 'declared_value';

    const CODE_DECLARED_VALUE = 'TINKOFF_CHECKOUT_DECLARED_VALUE';

    const PRODUCT_BLOCK_ID = 3;


    public static function getID($type)
    {
        $code = self::getCode($type);
        if (!$code) {
            return null;
        }

        $property = CIBlock::GetProperties(self::getProductBlockID(), [], ['CODE' => $code]);
        $property = $property->Fetch();
        if (!$property) {
            return null;
        }

        return isset($property['ID']) && $property['ID'] ? $property['ID'] : null;
    }

    public static function add($type)
    {
        $code = self::getCode($type);
        if (!$code) {
            return;
        }


        if (self::getID($type)) {
            return;
        }

        Loader::includeModule("iblock");

        if ($code == self::CODE_DECLARED_VALUE) {
            $property = new CIBlockProperty();
            $property->add([
                'CODE'          => $code,
                'NAME'          => 'Объявленная стоимость',
                'PROPERTY_TYPE' => 'N',
                'DETAIL_TEXT'   => 'Тинькофф Корзина. Объявленная стоимость товара для доставки Metaship',
                'PREVIEW_TEXT'  => ' Объявленная стоимость товара для доставки Metaship',
                'IBLOCK_ID'     => 3,
                'SEARCHABLE'    => 'N',
                'IS_REQUIRED'   => 'N',
                'ACTIVE'        => 'Y',
                'FEATURES'      => [
                    [
                        'IS_ENABLED' => 'Y',
                        'MODULE_ID'  => 'iblock',
                        'FEATURE_ID' => 'DETAIL_PAGE_SHOW'
                    ],
//                    [
//                        'IS_ENABLED' => 'Y',
//                        'MODULE_ID'  => 'catalog',
//                        'FEATURE_ID' => 'IN_BASKET'
//                    ],
                    [
                        'IS_ENABLED' => 'Y',
                        'MODULE_ID'  => 'iblock',
                        'FEATURE_ID' => 'LIST_PAGE_SHOW'
                    ],
                ]
            ], false, false);
        }
    }

    public static function getProductProperty($productID, $propertyType, $column = 'VALUE')
    {
        $result = ElementPropertyTable::getList([
            'filter' => [
                '=IBLOCK_PROPERTY_ID' => BlockProperty::getID($propertyType),
                '=IBLOCK_ELEMENT_ID'  => $productID,
            ]
        ]);

        $result = $result->Fetch();
        return isset($result[$column]) ? $result[$column] : null;
    }

    public static function remove($type)
    {
        $id = self::getID($type);
        CIBlockProperty::Delete($id);
    }


    private static function getCode($type)
    {
        switch ($type) {
            case self::TYPE_DECLARED_VALUE:
                return self::CODE_DECLARED_VALUE;
        }

        return null;
    }

    private static function getProductBlockID()
    {
        return self::PRODUCT_BLOCK_ID;
    }
}