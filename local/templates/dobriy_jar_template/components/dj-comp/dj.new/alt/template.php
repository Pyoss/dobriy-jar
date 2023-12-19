<?php


/** @var array $arParams */
/** @var array $arResult */

?>
<section class="hit section">
    <div class="hit__title container__title">
        <span class="container__title-current">Новинки</span>
        <span class="container__title-slash">/</span>
        <a class="container__title-link" href="/sale/novinki/">Показать всё</a>
    </div>
    <div class="hit__list row-view">
        <?

        global $arrFilterNew;
        if (!is_array($arrFilterNew))
            $arrFilterNew = array();
        foreach ($arResult['ITEMS'] as $ITEM){
            $arrFilterNew['ID'][] = $ITEM['ID'];
        }

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.top",
            "hit_alt",
            array(
                "IBLOCK_TYPE" => 'catalog',
                "IBLOCK_ID" => 2,
                "ELEMENT_SORT_FIELD" => "sort",//$arParams["ELEMENT_SORT_FIELD"],
                "ELEMENT_SORT_ORDER" => "asc",//$arParams["ELEMENT_SORT_ORDER"],
                "BASKET_URL" => '/personal/cart/ ',
                "ACTION_VARIABLE" => 'action',
                "PRODUCT_ID_VARIABLE" => 'id',
                "PRODUCT_QUANTITY_VARIABLE" => 'quantity',
                "PRODUCT_PROPS_VARIABLE" => 'prop',
                "PRICE_CODE" => ['RETAIL_PRICE'],
                "SHOW_PRICE_COUNT" => 1,
                "PRICE_VAT_INCLUDE" => 1,
                "ADD_PROPERTIES_TO_BASKET" => 'Y',
                "PARTIAL_PRODUCT_PROPERTIES" => 'N',
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "CACHE_FILTER" => 'Y',
                'HIDE_NOT_AVAILABLE' => 'N',
                 'HIDE_NOT_AVAILABLE_OFFERS'=> 'N',
                "OFFERS_CART_PROPERTIES" => 'ARTNUMBER',
                "OFFERS_FIELD_CODE" => ['NAME', 'ID'],
                "OFFERS_PROPERTY_CODE" => ['ARTNUMBER', 'MORE_PHOTO', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'],
                "OFFERS_SORT_FIELD" => 'sort',
                "OFFERS_SORT_ORDER" => 'asc',
                'CONVERT_CURRENCY' => 'N',
                'PRODUCT_DISPLAY_MODE' => 'Y',
                'OFFERS_LIMIT' => 0,
                'PRODUCT_SUBSCRIPTION' => 'Y',
                'SHOW_DISCOUNT_PERCENT' => 'Y',
                'SHOW_OLD_PRICE' => 'Y',
                "FILTER_NAME" => "arrFilterNew",
            ),
            $component, array("HIDE_ICONS" => "Y")
        ); ?>
    </div>
</section>

