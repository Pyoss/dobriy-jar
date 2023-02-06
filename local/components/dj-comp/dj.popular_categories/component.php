<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var DJbannersComponent $this */
/** @var array $arResult */
/** @var array $arParams */


$arResult['SECTIONS'] = array();
if ($arParams['CATEGORY_ID']){
    foreach ($arParams['CATEGORY_ID'] as $section_id){
        $rsSections = \Bitrix\Iblock\SectionTable::getList(
            array('select' => array('DETAIL_PICTURE', 'NAME', 'CODE'),
                'filter' => array('ID' => $section_id))
        );
        $arResult['SECTIONS'][] = $rsSections -> fetch();
    }
}


$this->IncludeComponentTemplate();