<?php
/** @var array $arParams */
/** @var array $arResult */

$cmp = $this -> getComponent();
if($cmp -> isMobile()){

    $arResult['images']['BACKGROUND'] = $cmp -> formatImage(
        $arParams['BACKGROUND_MOBILE'],
        array('width' => 1470, 'height' => 1342),
        array('width' => 1237, 'height' => 150));
} else {

    $arResult['images']['BACKGROUND'] = $cmp -> formatImage(
        $arParams['BACKGROUND'],
        array('width' => 1470, 'height' => 1342),
        array('width' => 2000, 'height' => 2000));
}

$arResult['images']['vb'] = $cmp -> formatImage(
    $arParams['VIBER_LOGO'],
    array('width' => 200, 'height' => 200),
    array('width' => 200, 'height' => 200));

$arResult['images']['tg'] = $cmp -> formatImage(
    $arParams['TG_LOGO'],
    array('width' => 200, 'height' => 200),
    array('width' => 200, 'height' => 200));

$arResult['images']['ws'] = $cmp -> formatImage(
    $arParams['WHATSAPP_LOGO'],
    array('width' => 200, 'height' => 200),
    array('width' => 200, 'height' => 200));