<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentDescription = array(
    'NAME' => 'Фильтр категорий',
    'DESCRIPTION' => 'Умный фильтр для ajax-запросов с фильтрацией списка товаров по категориям',
    'PATH' => array(
        'ID' => 'dj_components',
        'CHILD' => array(
            'ID' => 'catalog.sections_filter',
            'NAME' => 'Фильтр категорий'
        )
    ),
);
