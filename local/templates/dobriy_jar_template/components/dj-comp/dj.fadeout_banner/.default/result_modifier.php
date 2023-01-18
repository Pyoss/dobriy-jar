<?php
/** @var array $arParams */
/** @var array $arResult */

use DJScripts\ImgRef;

$rsBanners = \Bitrix\Iblock\ElementTable::getList([
        'filter' => array(
            'IBLOCK_SECTION_ID' => 122,
            'IBLOCK_ID' => 5,
            'ACTIVE' => 'Y'
        ),
        'select' => array(
            'ID', 'DETAIL_PICTURE', 'CODE', 'PREVIEW_PICTURE'
        ),
        'order' => array('SORT' => 'asc')]
);

while ($arBanners = $rsBanners -> fetch()){
    $arResult['BANNERS'][] = $arBanners;
}

foreach ($arResult['BANNERS'] as &$BANNER){
    $BANNER['img'] =
        ImgRef::optimizeImg(
            $BANNER['DETAIL_PICTURE'],
            array('width' => 1470, 'height' => 1342),
            array('width' => 1237, 'height' => 150));
}
