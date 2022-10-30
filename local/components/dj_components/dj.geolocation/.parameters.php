<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Получение списка типов инфоблоков
$dbIBlockTypes = CIBlockType::GetList(array("SORT"=>"ASC"), array("ACTIVE"=>"Y"));
while ($arIBlockTypes = $dbIBlockTypes->GetNext())
{
    $paramIBlockTypes[$arIBlockTypes["ID"]] = $arIBlockTypes["ID"];
}

// Получение списка инфоблоков заданного типа
$dbIBlocks = CIBlock::GetList(
    array("SORT"  =>  "ASC"),
    array("ACTIVE"    =>  "Y",
        "TYPE"      =>  $arCurrentValues["IBLOCK_TYPE"]));

while ($arIBlocks = $dbIBlocks->GetNext())
{
    $paramIBlocks[$arIBlocks["ID"]] = "[" . $arIBlocks["ID"] . "] " . $arIBlocks["NAME"];
}

// Формирование массива параметров
$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(
        "IBLOCK_TYPE"   =>  array(
            "PARENT"    =>  "BASE",
            "NAME"      =>  "Тип инфоблока",
            "TYPE"      =>  "LIST",
            "VALUES"    =>  $paramIBlockTypes,
            "REFRESH"   =>  "Y",
            "MULTIPLE"  =>  "N",
        ),
        "REDIRECT_QUERY"   =>  array(
            "PARENT"    =>  "BASE",
            "NAME"      =>  "Ключ автоматической переадресации",
            "TYPE"      =>  "STRING",
        ),
    ),
);

if ($paramIBlocks){
    $arComponentParameters['PARAMETERS']['IBLOCK_ID'] =  array(
        "PARENT"    =>  "BASE",
        "NAME"      =>  "Инфоблок",
        "TYPE"      =>  "LIST",
        "VALUES"    =>  $paramIBlocks,
        "REFRESH"   =>  "Y",
        "MULTIPLE"  =>  "N",
    );
}

// Получение списка свойств инфоблока
$resProps = \Bitrix\Iblock\PropertyTable::getList(
    array('select' => array('ID', 'NAME'), 'filter' => array('IBLOCK_ID' => $arCurrentValues["IBLOCK_ID"]))
);
while ($arProp = $resProps->fetch())
{
    $arProps[$arProp["ID"]] = "[" . $arProp["ID"] . "] " . $arProp["NAME"];
}

if ($arProps){
    $arComponentParameters['PARAMETERS']['DOMAIN_PROP_ID'] =  array(
        "PARENT"    =>  "BASE",
        "NAME"      =>  "Свойство привязки домена",
        "TYPE"      =>  "LIST",
        "VALUES"    =>  $arProps,
        "REFRESH"   =>  "Y",
        "MULTIPLE"  =>  "N",
    );
}