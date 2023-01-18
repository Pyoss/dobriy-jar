<?php
/** @var array $arParams */
/** @var array $arResult */

$cmp = $this -> getComponent();

$arResult['images']['BACKGROUND'] = $cmp -> formatImage(
    $arParams['BACKGROUND_IMAGE'],
    array('width' => 1470, 'height' => 1342),
    array('width' => 1237, 'height' => 150));

$arResult['images']['BACKGROUND_MOBILE'] = $cmp -> formatImage(
    $arParams['BACKGROUND_IMAGE_MOBILE'],
    array('width' => 1470, 'height' => 1342),
    array('width' => 1237, 'height' => 150));

$arResult['images'][0] = $cmp -> formatImage(
    $arParams['IMAGE_CALC'],
    array('width' => 350, 'height' => 350),
    array('width' => 350, 'height' => 350));

$arResult['images'][1] = $cmp -> formatImage(
    $arParams['IMAGE_BRANDS'],
    array('width' => 350, 'height' => 350),
    array('width' => 350, 'height' => 350));

$arResult['images'][2] = $cmp -> formatImage(
    $arParams['IMAGE_SHIPMENT'],
    array('width' => 350, 'height' => 350),
    array('width' => 350, 'height' => 350));


$arResult['images'][3] = $cmp -> formatImage(
    $arParams['IMAGE_INSTALLMENT'],
    array('width' => 350, 'height' => 350),
    array('width' => 350, 'height' => 350));
