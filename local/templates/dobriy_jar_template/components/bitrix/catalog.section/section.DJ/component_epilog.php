<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();
$FILTER_IBLOCK_ID = 11;
// Ищем переопределение SEO от фильтра
$filter_url = $_SERVER['SCRIPT_URL'];
$element = \Bitrix\Iblock\ElementTable::getList(['filter' => ['IBLOCK_ID' => $FILTER_IBLOCK_ID, 'CODE' => $filter_url, 'ACTIVE' => 'Y'], 'select' => ['ID', 'DETAIL_TEXT']])->Fetch();
if ($element) {
    $ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($FILTER_IBLOCK_ID, $element['ID']);
    $values = $ipropElementValues->getValues();
    $GLOBALS['SEO_PROP'] = [
        'description' => $values['ELEMENT_META_DESCRIPTION'],
        'keywords' => $values['ELEMENT_META_KEYWORDS'],
        'title' => $values['ELEMENT_META_TITLE']];
    $arResult['PATH'][] = ['NAME' => $values['ELEMENT_PAGE_TITLE'], 'SECTION_PAGE_URL' => $filter_url];
    $arResult['DESCRIPTION'] = $element['DETAIL_TEXT'];
}


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
$GLOBALS['show_canonical'] = 'Y';