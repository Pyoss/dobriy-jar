<?php
/** @var array $arParams */
/** @var array $arResult */

$component = $this -> getComponent();
// Getting blog articles
$rsLinks = \Bitrix\Iblock\ElementTable::getList([
        'filter' => array(
            'IBLOCK_ID' => 7,
            'ID' => $arParams['CATEGORY_ID']
        ),
        'select' => array(
            'ID', 'NAME', 'DETAIL_PICTURE',
            'PREVIEW_PICTURE', 'CODE', 'SECTION_CODE' => 'IBLOCK_SECTION.CODE'
        ),
        'limit' => 4]
);

while ($link = $rsLinks->Fetch()) {
    $link['CODE'] = 'https://blog.dobriy-jar.ru/' . $link['SECTION_CODE'] . '/' . $link ['CODE'] . '/';
    $link['IMG'] = $component -> formatImage(
        $link['DETAIL_PICTURE'],
        array('width' => 630, 'height' => 630),
        array('width' => 630, 'height' => 630));
    $link['MOB_IMG'] = $component -> formatImage(
        $link['PREVIEW_PICTURE'],
        array('width' => 630, 'height' => 630),
        array('width' => 630, 'height' => 630));
    $arResult['LINKS'][] = $link;
}
