<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

// -------------------------------- Тут встроенный компонент битрикс делает какую-то кашу, сохраню картинки

CModule::IncludeModule("landing");

$productGallery = $arResult['MORE_PHOTO'];
$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
$hasOffers = (bool)$arResult['OFFERS'];

// ----------------------- Составляем VIEW товара, отбирая и редактируя отображаемые элементы -------------- //
if ($hasOffers) {

    // -------------------------------- Определение текущего предложения из URL или сортировки. ---------------- //
    // -------------------------------- При некорректном URL возвращаем 404. ----------------------------------- //
    $arResult['CURRENT_OFFER'] = false;
    function get_sort($arr) {
        $min = $arr[0];
        foreach($arr as $obj) {
            $sort = (int) $obj['SORT'];
            if ($sort < (int) $min['SORT']) {
                $min = $obj;
            }
        }
        return $min;
    }
    if (!$arParams['CURRENT_OFFER_CODE']) {
        $arResult['CURRENT_OFFER'] = get_sort($arResult['OFFERS']);
    } else {
        foreach ($arResult['OFFERS'] as $offer){
            if ($offer['CODE'] == $arParams['CURRENT_OFFER_CODE']) {
                $arResult['CURRENT_OFFER'] = $offer;
                $arResult['META_TAGS']['BROWSER_TITLE'] = $arResult['CURRENT_OFFER']['NAME'];
                break;
            }
        }
    }

    // -----------------------------------------------------------------------------------------------------------//

    $arResult['VIEW']['NAME'] = DJMain::replaceProductType($arResult['PROPERTIES']['PRODUCT_TYPE']['VALUE'],
        $arResult['CURRENT_OFFER']['NAME']);
    $arResult['VIEW']['ARTNUMBER'] = $arResult['CURRENT_OFFER']['PROPERTIES']['ARTNUMBER']['VALUE'];
    $arResult['VIEW']['PRICE'] = $arResult['CURRENT_OFFER']['ITEM_PRICES'][0]['PRINT_PRICE'];
    $arResult['VIEW']['PERCENT'] = $arResult['CURRENT_OFFER']['ITEM_PRICES'][0]['PERCENT'];
    $arResult['VIEW']['RAW_PRICE'] = $arResult['CURRENT_OFFER']['ITEM_PRICES'][0]['PRICE'];
    $arResult['VIEW']['DISCOUNT'] = $arResult['CURRENT_OFFER']['ITEM_PRICES'][0]['DISCOUNT'];
    $arResult['VIEW']['BASE_PRICE'] = $arResult['CURRENT_OFFER']['ITEM_PRICES'][0]['PRINT_BASE_PRICE'];
    $arResult['VIEW']['ID'] = $arResult['CURRENT_OFFER']['ID'];
    $arResult['VIEW']['DISPLAY_PROPERTIES'] = $arResult['CURRENT_OFFER']['DISPLAY_PROPERTIES'];

    // ---------------------------------------------- ЗАПОЛНЯЕМ СТАТИЧНУЮ ГАЛЕРЕЮ --------------------------------//

    $photo_gallery = array_merge([$arResult['CURRENT_OFFER']['DETAIL_PICTURE']],
        $arResult['CURRENT_OFFER']['MORE_PHOTO']);
    foreach ($photo_gallery as $photo){
        $arResult['VIEW']['GALLERY'][] = array(
            'src' => $photo['SRC'],
            'source' => 'offer',
            'resize' => CFile::ResizeImageGet(
                $photo['ID'],
                array('width'=>100, 'height'=>100),
                BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true));
    }

} else {
    $arResult['VIEW']['NAME'] = DJMain::replaceProductType($arResult['PROPERTIES']['PRODUCT_TYPE']['VALUE'],
        $arResult['NAME']);
    $arResult['VIEW']['ARTNUMBER'] = $arResult['PROPERTIES']['ARTNUMBER']['VALUE'];
    $arResult['VIEW']['ID']  = $arResult['ID'];
    $arResult['VIEW']['RAW_PRICE'] = $arResult['ITEM_PRICES'][0]['PRICE'];
    $arResult['VIEW']['PRICE'] = $arResult['ITEM_PRICES'][0]['PRINT_PRICE'];
    $arResult['VIEW']['DISCOUNT'] = $arResult['ITEM_PRICES'][0]['DISCOUNT'];
    $arResult['VIEW']['PERCENT'] = $arResult['ITEM_PRICES'][0]['PERCENT'];
    $arResult['VIEW']['BASE_PRICE'] = $arResult['ITEM_PRICES'][0]['PRINT_BASE_PRICE'];
    $arResult['VIEW']['AVAILABLE'] = $arResult['CATALOG_QUANTITY'] > 0;
}

$photo_gallery = !!$arResult['DETAIL_PICTURE'] ? array_merge([$arResult['DETAIL_PICTURE']],
    $productGallery) : $productGallery;
foreach ($photo_gallery as $photo) {
    $arResult['VIEW']['GALLERY'][] = array(
        'src' => $photo['SRC'],
        'source' => 'product',
        'resize' => CFile::ResizeImageGet(
            $photo['ID'],
            array('width' => 100, 'height' => 100),
            BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true));
}

if($arResult['OFFERS']){
    foreach ($arResult['JS_OFFERS'] as $iter => $jsOffer){
        // ---- #OFFER_JSON ----//
        $OFFER_DATA = array(
            'ID' => $jsOffer['ID'],
            'PRINT_PRICE' => $jsOffer['ITEM_PRICES'][0]['PRINT_PRICE'],
            'BASE_PRICE' => $jsOffer['ITEM_PRICES'][0]['PRINT_BASE_PRICE'],
            'RATIO_DISCOUNT' => $jsOffer['ITEM_PRICES'][0]['RATIO_DISCOUNT'],
            'QUANTITY' => $arResult['OFFERS'][$iter]['CATALOG_QUANTITY'],
            'NAME' =>  DJMain::replaceProductType($arResult['PROPERTIES']['PRODUCT_TYPE']['VALUE'],
                $jsOffer['NAME']),
            'GALLERY' => array_merge([$arResult['OFFERS'][$iter]['DETAIL_PICTURE']], $jsOffer['SLIDER'])
        );

        foreach ($jsOffer["DISPLAY_PROPERTIES"] as $prop){
            if($prop['CODE'] == 'ARTNUMBER'){
                $OFFER_DATA[$prop['CODE']] = $prop['VALUE'];

            }
        }

        $OFFER_CODE = '';
        foreach($jsOffer['TREE'] as $prop => $value){
            $OFFER_CODE .= (explode('_', $prop)[1]) . '_' . $value . ':';
        }

        $arResult['JS_OFFERS_MAP'][$OFFER_CODE] = $OFFER_DATA;
    }
}


// ------------------------------------------- КАРТИНКА ДЛЯ ХАРАКТЕРИСТИК --------------------------------- //
if (isset($arResult['PROPERTIES']['PROPS_PICTURE']['VALUE'])){
    $arResult['PROP_PICT'] = CFile::GetPath($arResult['PROPERTIES']['PROPS_PICTURE']['VALUE']);
} else if($hasOffers&&$arResult['CURRENT_OFFER']['DETAIL_PICTURE']){
    $arResult['PROP_PICT'] = $arResult['CURRENT_OFFER']['DETAIL_PICTURE']['SRC'];
} else {
    $arResult['PROP_PICT'] = $arResult['DETAIL_PICTURE']['SRC'];
}



// ------------------------------------------- КОМПЛЕКТАЦИЯ --------------------------------- //
$property_compilation = array();
if (isset($arResult['PROPERTIES']['COMPLECT']['VALUE'])){
    foreach ($arResult['PROPERTIES']['COMPLECT']['VALUE'] as $prop_array){


        $prop_array = json_decode(html_entity_decode($prop_array), true);
        $item_data = \Bitrix\Iblock\ElementTable::getById($prop_array['item']) -> fetch();
        $section_data = \Bitrix\Iblock\SectionElementTable::getList(['filter' => ['IBLOCK_ELEMENT_ID' => $prop_array['item']],
            'select' => ['IBLOCK_SECTION_ID']]);
        $section_data = $section_data -> fetch();
        if (isset($section_data['IBLOCK_SECTION_ID'])){

            $section_code = (\Bitrix\Iblock\SectionTable::getById($section_data['IBLOCK_SECTION_ID']) ->fetch())['CODE'];
        }
        if (isset($item_data['PREVIEW_PICTURE'])){
            $prop_array['img_src'] = CFile::GetPath($item_data['PREVIEW_PICTURE']);
        } else {
            $prop_array['img_src'] = DJMain::IMAGE_TEMPLATE_SRC;
        }
        $prop_array['name'] = $prop_array['comment'].'</b>';
        $prop_array['code'] = $section_code . '/' . $item_data['CODE'];
        $prop_array['active'] = $item_data['ACTIVE'];
        $arResult['COMPLECTATION'][$prop_array['group']][] = $prop_array;
    }
}
if($arResult['PROPERTIES']['BRAND']){
    $brand = \Bitrix\Iblock\ElementTable::getList(['filter' => ['IBLOCK_ID' => 6,
        'ID' => $arResult['PROPERTIES']['BRAND']['VALUE']], 'select' => ['CODE', 'ID', 'PREVIEW_PICTURE']]) -> Fetch();
    $brand['PREVIEW_PICTURE'] = CFile::GetPath($brand['PREVIEW_PICTURE']);
    $arResult['BRAND'] = $brand;
}
DJMain::consoleString($arResult);
/*
$arResult['DETAIL_TEXT'] = str_replace('<img', '<img class="lazy"', $arResult['DETAIL_TEXT']);
$arResult['DETAIL_TEXT'] = str_replace('src', ' data-src', $arResult['DETAIL_TEXT']);
*/