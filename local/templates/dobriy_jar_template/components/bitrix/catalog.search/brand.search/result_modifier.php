<?php

if ($_GET['PRICE_SORT']){
    $arParams["ELEMENT_SORT_FIELD"] = 'SCALED_PRICE_2';
    $arParams["ELEMENT_SORT_ORDER"] = $_GET['PRICE_SORT'];
}

if ($_COOKIE['VIEW_MODE']){
    $arParams["VIEW_MODE"] = $_COOKIE['VIEW_MODE'];
}