<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues Текущие выставленные значения параметров*/
/** @var array $arComponentParameters Настройки параметров */
/** @var array $componentPath Путь к компоненту */

$arComponentParameters['PARAMETERS']['BACKGROUND_IMAGE'] = array(
    "NAME" => 'Фон контейнера',
    "TYPE" => "FILE",
    "FD_TARGET" => "F",
    "FD_EXT" => array('png', 'jpg', 'jpeg', 'gif'),
    "FD_UPLOAD" => true,
    "FD_USE_MEDIALIB" => true,
    "FD_MEDIALIB_TYPES" => Array('image')
);
$arComponentParameters['PARAMETERS']['BACKGROUND_IMAGE_MOBILE'] = array(
    "NAME" => 'Фон контейнера мобильный',
    "TYPE" => "FILE",
    "FD_TARGET" => "F",
    "FD_EXT" => array('png', 'jpg', 'jpeg', 'gif'),
    "FD_UPLOAD" => true,
    "FD_USE_MEDIALIB" => true,
    "FD_MEDIALIB_TYPES" => Array('image')
);