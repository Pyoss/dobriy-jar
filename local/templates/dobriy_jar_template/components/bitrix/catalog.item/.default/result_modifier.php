<?php

$arResult['LOADING'] = true;
if ($_GET['reload']){
    $arResult['LOADING'] = false;
}
$product_type = $arResult['ITEM']['PROPERTIES']['PRODUCT_TYPE']['VALUE'];
$arResult['ITEM']['VIEW']['NAME'] = DJMain::replaceProductType($product_type, $arResult['ITEM']['NAME']);
$preview_picture = array();
// Ищем картинку
if ($arResult['ITEM']['PREVIEW_PICTURE']){
    if (is_numeric($arResult['ITEM']['PREVIEW_PICTURE'])){
        $preview_picture = CFile::GetPath($arResult['ITEM']['PREVIEW_PICTURE']);
    } else {
        $preview_picture = $arResult['ITEM']['PREVIEW_PICTURE'];
    }
} elseif ($arResult['ITEM']['DETAIL_PICTURE']) {
    if (is_numeric($arResult['ITEM']['DETAIL_PICTURE'])) {
        $preview_picture = CFile::GetPath($arResult['ITEM']['DETAIL_PICTURE']);
    } else {
        $preview_picture = $arResult['ITEM']['DETAIL_PICTURE'];
    }
}
// Ставим картинку по умолчанию

if (!$preview_picture) {
    $preview_picture['SRC'] = DJMain::IMAGE_TEMPLATE_SRC;
}
$arResult['ITEM']['PICTURE'] = $preview_picture;
$ITEM = $arResult['ITEM'];
if($ITEM['OFFERS']){
    foreach ($ITEM['JS_OFFERS'] as $jsOffer){
        // ---- #OFFER_JSON ----//
        $OFFER_DATA = array(
            'ID' => $jsOffer['ID'],
            'PRINT_PRICE' => $jsOffer['ITEM_PRICES'][0]['PRINT_PRICE'],
            'BASE_PRICE' => $jsOffer['ITEM_PRICES'][0]['PRINT_BASE_PRICE'],
            'RATIO_DISCOUNT' => $jsOffer['ITEM_PRICES'][0]['RATIO_DISCOUNT'],
            'NAME' => DJMain::replaceProductType($product_type, $jsOffer['NAME']),
            'GALLERY' => array($jsOffer['PREVIEW_PICTURE'] ?: array("SRC" => DJMain::IMAGE_TEMPLATE_SRC)),
        );

        foreach ($jsOffer["DISPLAY_PROPERTIES"] as $prop){
            $OFFER_DATA[$prop['CODE']] = $prop['VALUE'];
        }

        $OFFER_CODE = '';
        foreach($jsOffer['TREE'] as $prop => $value){
            $OFFER_CODE .= (explode('_', $prop)[1]) . '_' . $value . ':';
        }

        $arResult['JS_OFFERS_MAP'][$OFFER_CODE] = $OFFER_DATA;
    }
}

// Подсчитываем отзывы
$resReviews = \Bitrix\Iblock\ElementPropertyTable::getList(
    ['select' => ['IBLOCK_ELEMENT_ID'],
        'filter' => ['IBLOCK_PROPERTY_ID' => 92, 'VALUE' => $arResult['ITEM']['ID']]]
);
$arResult['ITEM']['REVIEWS']['NUMBER'] = 0;
while ($arReview = $resReviews -> fetch()){
    $arResult['ITEM']['REVIEWS']['NUMBER'] += 1;
}