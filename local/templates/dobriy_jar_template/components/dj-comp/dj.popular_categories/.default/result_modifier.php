<?php
/** @var array $arParams */
/** @var array $arResult */


$cmp = $this->getComponent();

foreach ($arResult['SECTIONS'] as &$SECTION) {
    $SECTION['img'] =
        $cmp->formatImage(
            $SECTION['DETAIL_PICTURE'],
            array('width' => 250, 'height' => 250),
            array('width' => 250, 'height' => 250));
}

for ($i =0; $i <= (count($SECTION) % 3); $i ++ ){
    $arResult['SECTIONS'][] = 'blank';
}

$arResult['BACKGROUND'] =
    $cmp->formatImage(
        $arParams['BACKGROUND'],
        array('width' => 1000, 'height' => 1000),
        array('width' => 1440, 'height' => 1440));
