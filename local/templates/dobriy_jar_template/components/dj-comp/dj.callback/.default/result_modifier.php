<?php
/** @var array $arParams */
/** @var array $arResult */

$cmp = $this -> getComponent();

$arResult['images']['BACKGROUND'] = $cmp -> formatImage(
    $arParams['BACKGROUND_IMAGE'],
    array('width' => 1470, 'height' => 1342),
    array('width' => 1237, 'height' => 150));

