<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


DJMain::consoleString($arResult);
foreach ($arResult['BASKET'] as &$basketItem){
    $res = CIBlockElement::GetList(array(), array('ID' => $basketItem['PRODUCT_ID']), false,
        false, array('PROPERTY_ARTNUMBER', 'IBLOCK_ID', 'PROPERTY_PRODUCT_TYPE'));
    $arItem = $res -> fetch();
    $basketItem['ARTICLE'] = $arItem['PROPERTY_ARTNUMBER_VALUE'];
    if ($arItem['IBLOCK_ID'] == 2){
        $basketItem['PRODUCT_TYPE'] = $arItem['PROPERTY_PRODUCT_TYPE_VALUE'];
    } else if ($arItem['IBLOCK_ID'] == 3){
        $parentItem = CCatalogSku::getProductInfo($basketItem['PRODUCT_ID']);
        $parentRes = CIBlockElement::GetList(array(), array('ID' => $parentItem['ID']), false,
            false, array('PROPERTY_PRODUCT_TYPE'));
        $basketItem['PRODUCT_TYPE'] = $parentRes -> fetch()['PROPERTY_PRODUCT_TYPE_VALUE'];

    }
}