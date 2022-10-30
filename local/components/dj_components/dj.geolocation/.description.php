<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
    'NAME' => 'Геолокация ДЖ',
    'DESCRIPTION' => 'Компонент для геолокации Интернет-магазина',
    'PATH' => array(
        'ID' => 'dj_components',
            'CHILD' => array(
                'ID' => 'geolocation',
                'NAME' => 'Геолокация'
            )
        ),
    );
