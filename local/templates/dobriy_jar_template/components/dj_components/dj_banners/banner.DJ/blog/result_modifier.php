<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

// Getting blog articles
$rsBanners = \Bitrix\Iblock\ElementTable::getList([
        'filter' => array(
            'IBLOCK_ID' => 7,
            'ID' => array(6240, 6217,6225, 6123)
        ),
        'select' => array(
            'ID', 'NAME', 'DETAIL_PICTURE',
            'PREVIEW_PICTURE', 'CODE', 'SECTION_CODE' => 'IBLOCK_SECTION.CODE'
        ),
        'limit' => 3]
);

while ($banner = $rsBanners->Fetch()) {
    $banner['CODE'] = 'https://blog.dobriy-jar.ru/' . $banner['SECTION_CODE'] . '/' . $banner['CODE'] . '/';
    $banner['IMG'] = CFile::GetPath($banner['DETAIL_PICTURE']);
    $banner['MOB_IMG'] = CFile::GetPath($banner['PREVIEW_PICTURE']);
    $arResult['BANNER_DATA'][] = $banner;
}
