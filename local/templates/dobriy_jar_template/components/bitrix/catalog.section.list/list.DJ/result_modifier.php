<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionList $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 **/

use DJScripts\ImgRef;
CModule::IncludeModule('dj.imgref');
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
foreach ($arResult['SECTIONS'] as $SECTION){
    $arSectionView['RESIZED_IMAGES'] = ImgRef::optimizeImg($SECTION['DETAIL_PICTURE'], array('width' => 350, 'height' => 350));
    $arSectionView['PICTURE'] = CFile::GetPath($SECTION['DETAIL_PICTURE']) ? : DJMain::IMAGE_TEMPLATE_SRC;
    $arSectionView['SUBTITLE'] = '';
    $arSectionView['NAME'] = $SECTION['NAME'] ? : '*Blank name*';
    $arSectionView['LINK'] = '/catalog/' . $SECTION['CODE'] . '/'? : '';
    $arSectionView['DELETE_LINK'] = $SECTION['DELETE_LINK'];
    $arSectionView['EDIT_LINK'] = $SECTION['EDIT_LINK'];
    $arResult['VIEW'][] = $arSectionView;
}
