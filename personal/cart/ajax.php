<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
if ($_GET['action']):?>
<json><?
if ($_GET['action'] == 'ADD'){
    $ajax_basket = new AjaxBasket();
    $ajax_basket -> addItem((int)$_GET['product_id'],
        (int)$_GET['quantity'], true);
} else if ($_GET['action'] == 'UPDATE'){
    $ajax_basket = new AjaxBasket();
    $ajax_basket -> updateItem((int)$_GET['product_id'],
        (int)$_GET['quantity'], true);
} else if ($_GET['action'] == 'DELETE'){
    $ajax_basket = new AjaxBasket();
    $ajax_basket -> deleteItem((int)$_GET['product_id'],
        true);
}?></json><?php
endif;
if ($_GET['basket_html']){

    $APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "basket.DJ.ajax", array(
        "PATH_TO_ORDER" => "/personal/order/make/",
        "QUANTITY_FLOAT" => "N",
        "PRICE_VAT_SHOW_VALUE" => "Y",
        "TEMPLATE_THEME" => "site",
        "SET_TITLE" => "Y",
        "AJAX_OPTION_ADDITIONAL" => "",
        "COLUMNS_LIST_EXT" => array(
            0 => "PREVIEW_PICTURE",
            1 => "DISCOUNT",
            2 => "DELETE",
            3 => "DELAY",
            4 => "TYPE",
            5 => "SUM",
            6 => "PROPERTY_ARTNUMBER",
            7 => "PROPERTY_PRODUCT_TYPE",
        ),
        ),
        false
    );
}?>

