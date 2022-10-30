<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();
$FILTER_IBLOCK_ID = 11;
// Ищем переопределение SEO от фильтра
$filter_url = $_SERVER['SCRIPT_URL'];
DJMain::consoleString('Началась загрузка актуального эпилога');
DJMain::consoleString($arResult);
$GLOBALS['SEO_PROP'] = [
    'description' => $arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_DESCRIPTION'],
    'keywords' => $arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_DESCRIPTION'],
    'title' => $arResult['SECTION']['IPROPERTY_VALUES']['SECTION_META_TITLE']];
DJMain::consoleString($GLOBALS['SEO_PROP']);
/***
 * Устанавливаем title и description с учетом GEODJ
 */
foreach ($GLOBALS['SEO_PROP'] as $prop_id => $prop_value){
    DJMain::consoleString($prop_value);
    $APPLICATION->SetPageProperty($prop_id, $prop_value);
}
$title = $APPLICATION -> GetPageProperty('title');
DJgeo::geoReplace($title);
$APPLICATION -> SetPageProperty('title', $title);
$description = $APPLICATION -> GetPageProperty('description');
DJgeo::geoReplace($description);
$APPLICATION -> SetPageProperty('description', $description);