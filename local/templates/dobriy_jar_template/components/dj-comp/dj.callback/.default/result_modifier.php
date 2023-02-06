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
