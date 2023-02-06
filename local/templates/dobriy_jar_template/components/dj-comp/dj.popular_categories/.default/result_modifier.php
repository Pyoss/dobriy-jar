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

$length = count($arResult['SECTIONS']);
$i = $length % 3;
if ($i !== 0) {
    while ($i < 3) {
        $arResult['SECTIONS'][] = 'blank';
        $i++;
    }
}


$arResult['BACKGROUND'] =
    $cmp->formatImage(
        $arParams['BACKGROUND'],
        array('width' => 1000, 'height' => 2000),
        array('width' => 1440, 'height' => 1440));
