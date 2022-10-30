<?php
$arResult['ITEMS'] = array();
$arResult['SECTIONS'] = array();
foreach ($arResult['SEARCH'] as $elResult) {
    // если элемент поиска - товар
    if(is_numeric($elResult['ITEM_ID'][0])){
        $arItem = $elResult;
        $arItem['NAME'] = $elResult['TITLE'];
        $arResult['ITEMS'][$elResult['ITEM_ID']] = $arItem;
    // если элемент поиска - категория
    } elseif($elResult['ITEM_ID'][0] == 'S') {
        $arSection = $elResult;
        $arSection['NAME'] = $elResult['TITLE'];
        $arResult['SECTIONS'][] = $arSection;
    }
}

//СОЗДАЕМ КАРТУ АЙДИ ДЛЯ ЗАПРОСОВ В БД
$arResult['ITEM_IDS'] = array_map(function($item) {
    return $item['ITEM_ID'];
}, $arResult['ITEMS']);

$section_id_dict = array(); //массив категорий найденных элементов

// ДОСТАЕМ ОСНОВНЫЕ ПАРАМЕТРЫ
$dbItems = \Bitrix\Iblock\ElementTable::getList(array(
    'select' => array('ID', 'IBLOCK_SECTION_ID'),
    'filter' => array('IBLOCK_ID' => 2, '=ID' => $arResult['ITEM_IDS'])));
while ($row = $dbItems->fetch())
{
    $arResult['ITEMS'][$row['ID']] = array_merge($arResult['ITEMS'][$row['ID']], $row);
}

// ДОСТАЕМ ТИП ТОВАРА И АРТИКУЛ
$propRes = \Bitrix\Iblock\ElementPropertyTable::getList(array(
    "select" => array("ID", "*"),
    "filter" => array("=IBLOCK_ELEMENT_ID" => $arResult['ITEM_IDS'],'IBLOCK_PROPERTY_ID' => 39 // (тип товара)
    )
));

while($prop = $propRes->Fetch())
{
    // TODO: Заменить волшебное число 39 (тип товара)
    if ($prop['IBLOCK_PROPERTY_ID'] == 39){
        $arResult['ITEMS'][$prop['IBLOCK_ELEMENT_ID']]["PROPERTIES"]['PRODUCT_TYPE'] = $prop;
    }
}

// ДОСТАЕМ ЦЕНЫ






