<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "SECTION_NUMBER" => Array(
            "PARENT" => "BASE",
            "NAME" => 'Количество секций',
            "TYPE" => "INTEGER",
            "REFRESH" => "Y",
    )));

if ($arCurrentValues['SECTION_NUMBER']){
    for ($i = 1; $i <= $arCurrentValues['SECTION_NUMBER']; $i++){
        $arComponentParameters['PARAMETERS']['SECTION_NAME_' . $i] = array(
            "NAME" => 'Название секции ' . $i,
            'TYPE' => 'STRING',
        );
        $arComponentParameters['PARAMETERS']['SECTION_TEXT_' . $i] = array(
            "NAME" => 'Текст секции ' . $i,
            'TYPE' => 'STRING',
        );
        $arComponentParameters['PARAMETERS']['SECTION_LINK_' . $i] = array(
            "NAME" => 'Ссылка секции ' . $i,
            'TYPE' => 'STRING',
        );
        $arComponentParameters['PARAMETERS']['SECTION_BACKGROUND_' . $i] = array(
            "NAME" => 'Фон секции ' . $i,
            "TYPE" => "FILE",
            "FD_TARGET" => "F",
            "FD_EXT" => array('png', 'jpg', 'jpeg', 'gif'),
            "FD_UPLOAD" => true,
            "FD_USE_MEDIALIB" => true,
            "FD_MEDIALIB_TYPES" => Array('image')
        );

    }
}