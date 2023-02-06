<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues Текущие выставленные значения параметров*/
/** @var array $arComponentParameters Настройки параметров */
/** @var array $componentPath Путь к компоненту */

CModule::IncludeModule("iblock");

$rsSection = \Bitrix\Iblock\SectionTable::getList(array(
    'filter' => array(
        'IBLOCK_ID' => 2,
    ),
    'select' =>  array(
        'ID',
        'NAME',
    ),
));

while ($section = $rsSection -> fetch()){
    $arSectionsParameters[$section['ID']] = $section['NAME'];
}

$arComponentParameters['PARAMETERS']['CATEGORY_ID'] = array(
    "PARENT"=> 'BASE',
    "NAME" => 'Категории',
    "TYPE" => "LIST",
    "MULTIPLE" => "Y",
    "VALUES" => $arSectionsParameters
);