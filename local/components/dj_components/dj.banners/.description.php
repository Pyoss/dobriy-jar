<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
    'NAME' => 'Баннеры ДЖ',
    'DESCRIPTION' => 'Универсальный компонент для вывода нескольких типов баннеров',
    'PATH' => array(
        'ID' => 'dj_components',
            'CHILD' => array(
                'ID' => 'banner',
                'NAME' => 'Баннер'
            )
        ),
    );
