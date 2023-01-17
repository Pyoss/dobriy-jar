<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues Текущие выставленные значения параметров*/
/** @var array $arComponentParameters Настройки параметров */
/** @var array $componentPath Путь к компоненту */

CModule::IncludeModule("iblock");

$rsBlog = \Bitrix\Iblock\ElementTable::getList(array(
    'filter' => array(
        'IBLOCK_ID' => 7,
    ),
    'select' =>  array(
        'ID',
        'NAME',
    ),
));

while ($blog = $rsBlog -> fetch()){
    $arSectionsParameters[$blog['ID']] = $blog['NAME'];
}

$arComponentParameters['PARAMETERS']['CATEGORY_ID'] = array(
    "PARENT"=> 'BASE',
    "NAME" => 'Статьи',
    "TYPE" => "LIST",
    "MULTIPLE" => "Y",
    "VALUES" => $arSectionsParameters
);