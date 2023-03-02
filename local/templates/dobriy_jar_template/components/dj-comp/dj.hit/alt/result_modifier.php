<?php
/** @var array $arParams */
/** @var array $arResult */

$component = $this -> getComponent();

foreach ($arResult['ITEMS'] as &$ITEM){
    $ITEM['img'] =
        $component -> formatImage(
            $ITEM['PREVIEW_PICTURE'],
            array('width' => 300, 'height' => 300),
            array('width' => 300, 'height' => 300));
}