<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();}

// Getting blog articles
$rsBanners = \Bitrix\Iblock\ElementTable::getList([
    'filter' => array(
        'IBLOCK_ID' => 6
        ),
    'select' => array(
        'PREVIEW_PICTURE'
    )]
);

while ($banner = $rsBanners -> Fetch()){

    $banner['IMG'] = CFile::GetPath($banner['PREVIEW_PICTURE']);
    $arResult['BANNER_DATA'][] = $banner;
}
