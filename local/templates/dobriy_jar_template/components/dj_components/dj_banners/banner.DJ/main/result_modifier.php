<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();}

$rsBanners = \Bitrix\Iblock\ElementTable::getList([
    'filter' => array(
        'IBLOCK_SECTION_ID' => 122,
        'IBLOCK_ID' => 5
        ),
    'select' => array(
        'ID', 'DETAIL_PICTURE', 'CODE'
    )]
);

while ($banner = $rsBanners -> Fetch()){
    $banner['IMG'] = CFile::GetPath($banner['DETAIL_PICTURE']);
    $arResult['BANNER_DATA'][] = $banner;
}
