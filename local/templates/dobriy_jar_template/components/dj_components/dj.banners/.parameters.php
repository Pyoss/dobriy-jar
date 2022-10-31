<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues Текущие выставленные значения параметров*/
/** @var array $arComponentParameters Настройки параметров */
/** @var array $componentPath Путь к компоненту */

CModule::IncludeModule("iblock");

$arBannerType = array(
    'blog' => 'blog',
    'brands' => 'brands',
    'filter' => 'filter',
    'hit' => 'hit',
    'main' => 'main',
    'new' => 'blog',
    'popular' => 'popular',
    'blog_main' => 'blog_main',
    'blog_video' => 'blog_video',
);

$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "BANNER_ID" => array(
            "PARENT" => "BASE",
            "NAME" => "ID баннера",
            "TYPE" => "STRING",
        ),
        "BANNER_TYPE" => array(
            "PARENT" => "BASE",
            "NAME" => "Тип баннера",
            "TYPE" => "LIST",
            "VALUES" => $arBannerType,
            "REFRESH" => "Y",
        ),
        "SLIDERS_VIEW" => array(
            "PARENT" => "VISUAL",
            "NAME" => "Количество слайдов",
            "TYPE" => "INTEGER",
            "MULTIPLE" => "N",
            "DEFAULT" => 1,
        ),
        "SCROLL_SPEED" => array(
            "PARENT" => "VISUAL",
            "NAME" => "Скорость прокрутки (сек)",
            "TYPE" => "INTEGER",
            "MULTIPLE" => "N",
            "DEFAULT" => 5
        ),
        "CONTROLS" => array(
            "PARENT" => "VISUAL",
            "NAME" => "Управление баннером",
            "TYPE" => "LIST",
            "VALUES" => array(
                'sides' => 'По бокам',
                'center' => 'По центру',
                'HIDDEN' => 'Нет'),
        ),
        "ENDLESS" => array(
            "PARENT" => "VISUAL",
            "NAME" => "Зацикленный",
            "TYPE" => "CHECKBOX",
            "MULTIPLE" => "N",
            "DEFAULT" => "Y"
        ),
        "WIDTH" => array(
            "PARENT" => "VISUAL",
            "NAME" => "Ширина",
            "TYPE" => "INTEGER",
            "MULTIPLE" => "N",
            "DEFAULT" => 1000
        ),
        "MIN-SLIDE-WIDTH" => array(
            "PARENT" => "VISUAL",
            "NAME" => "Минимальная ширина слайда",
            "TYPE" => "INTEGER",
            "MULTIPLE" => "N",
            "DEFAULT" => 1000
        ),
        "HEIGHT" => array(
            "PARENT" => "VISUAL",
            "NAME" => "Высота",
            "TYPE" => "INTEGER",
            "MULTIPLE" => "N",
            "DEFAULT" => 400
        )
    ),
);

if ($arCurrentValues['BANNER_TYPE'] == 'popular'){
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
}
