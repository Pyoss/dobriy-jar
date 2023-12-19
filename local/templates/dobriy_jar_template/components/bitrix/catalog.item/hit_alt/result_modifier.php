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
DJMain::consoleString($preview_picture);
$arResult['ITEM']['PICTURE'] = $preview_picture;