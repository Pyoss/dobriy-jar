<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arSectionIDS = array(); //массив категорий найденных элементов
$foundSections = array();

// ДОСТАЕМ КАТЕГОРИИ
$dbItems = \Bitrix\Iblock\ElementTable::getList(array(
    'select' => array('ID', 'IBLOCK_SECTION_ID'),
    'filter' => array('IBLOCK_ID' => 2, '=ID' => array_values($arParams['ELEMENTS_ARRAY']))));
while ($row = $dbItems->fetch())
{
    $arSectionIDS[$row['IBLOCK_SECTION_ID']] = (int)$arSectionIDS[$row['IBLOCK_SECTION_ID']] + 1;
}


foreach ($arSectionIDS as $sectionID => $quantity){

    $parentSections = [];
    $parentSectionIterator = \Bitrix\Iblock\SectionTable::getList([
        'select' => [
            'SECTION_ID' => 'SECTION_SECTION.ID',
            'IBLOCK_SECTION_ID' => 'SECTION_SECTION.IBLOCK_SECTION_ID',
        ],
        'filter' => [
            '=ID' => $sectionID,
        ],
        'runtime' => [
            'SECTION_SECTION' => [
                'data_type' => '\Bitrix\Iblock\SectionTable',
                'reference' => [
                    '=this.IBLOCK_ID' => 'ref.IBLOCK_ID',
                    '>=this.LEFT_MARGIN' => 'ref.LEFT_MARGIN',
                    '<=this.RIGHT_MARGIN' => 'ref.RIGHT_MARGIN',
                ],
                'join_type' => 'inner'
            ],
        ],
    ]);

    while ($parentSection = $parentSectionIterator->fetch()) {
        $parentSections[] = $parentSection;
    }
    // Определяем отображать корневую или вторую по счету категорию
    $innerSectionIndex = (int)(!(count($parentSections) <= 2));
    $parentID = $parentSections[$innerSectionIndex]['SECTION_ID'];

    if ($foundSections[$parentID]){
        $foundSections[$parentID]['QUANTITY'] += $quantity;
    } else {
        $foundSections[$parentID] = array('QUANTITY' => $quantity, 'SECTION_ID' => $parentSections[$innerSectionIndex]['SECTION_ID']);
    }
}
foreach($foundSections as &$foundSection) {
    $rsSections = \Bitrix\Iblock\SectionTable::getRow([
        'select' => [
            'NAME'
        ],
        'filter' => [
            '=ID' => $foundSection['SECTION_ID']
        ]
    ]);
    $foundSection = array_merge($foundSection, $rsSections);
}
$arResult['FOUND_SECTIONS'] = $foundSections;

$this->IncludeComponentTemplate()
?>