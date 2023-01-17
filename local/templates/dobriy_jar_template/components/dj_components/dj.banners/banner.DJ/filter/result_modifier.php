<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();}

$rsBanners = \Bitrix\Iblock\ElementTable::getList([
        'filter' => array(
            'IBLOCK_SECTION_ID' => 123,
            'IBLOCK_ID' => 5,
            'ACTIVE' => 'Y'
        ),
        'select' => array(
            'ID', 'DETAIL_PICTURE', 'CODE'
        ),
        'order' => array('SORT' => 'asc')]
);
$index = 0;
while ($banner = $rsBanners -> Fetch()) {
    if ($banner['DETAIL_PICTURE']) {
        $banner['IMG_FILE'] = CFile::GetByID($banner['DETAIL_PICTURE'])->fetch();
        $banner['IMG'] = CFile::GetPath($banner['DETAIL_PICTURE']);
    } else {
        $banner['IMG'] = DJMain::IMAGE_TEMPLATE_SRC;
    }

    if ($banner['PREVIEW_PICTURE']) {
        $banner['MOBIMG_FILE'] = CFile::GetByID($banner['PREVIEW_PICTURE'])->fetch();
        $banner['MOBIMG'] = CFile::GetPath($banner['MOBIMG']);
    } else {
        $banner['MOBIMG'] = DJMain::IMAGE_TEMPLATE_SRC;
    }
    $banner['INDEX'] = ++$index;
    if (!$arResult['RATIO']) {
        $arResult['RATIO'] = round($banner['IMG_FILE']['HEIGHT'] / $banner['IMG_FILE']['WIDTH'], 3);
        $arResult['RATIO'] = $banner['IMG_FILE'] ? $arResult['RATIO'] : 0.3;
    }

    if (!$arResult['MOB_RATIO']){
        $arResult['MOB_RATIO'] = round($banner['MOBIMG_FILE']['HEIGHT'] / $banner['MOBIMG_FILE']['WIDTH'], 3);
        $arResult['MOB_RATIO'] = $banner['MOBIMG_FILE'] ? $arResult['MOB_RATIO'] : 0.5;
    }
    $arResult['BANNER_DATA'][] = $banner;
}