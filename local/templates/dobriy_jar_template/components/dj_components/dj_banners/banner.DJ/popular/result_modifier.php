<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();}


$arResult['BANNER_DATA'] = array();
if ($arParams['CATEGORY_ID']){
    foreach ($arParams['CATEGORY_ID'] as $section_id){
        $rsSections = \Bitrix\Iblock\SectionTable::getList(
            array('select' => array('DETAIL_PICTURE', 'NAME', 'CODE'),
                  'filter' => array('ID' => $section_id))
        );

        while($section = $rsSections -> Fetch()){
            $arResult['BANNER_DATA'][] = array(
                'NAME' => $section['NAME'],
                'CODE' => $section['CODE'],
                'PICTURE' => CFile::GetPath($section['DETAIL_PICTURE']),
                'PRICE' => DJMain::getLowestPrice($section_id));
        }
    }
}
