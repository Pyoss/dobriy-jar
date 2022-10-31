<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */
$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
$ajax_basket = new AjaxBasket();
$basket = $ajax_basket->getUserBasket();


if ($_COOKIE['VIEW_MODE']) {
    $arParams["VIEW_MODE"] = $_COOKIE['VIEW_MODE'];
}
$FILTER_IBLOCK_ID = 11;

if ($_COOKIE['VIEW_MODE']) {
    $arParams["VIEW_MODE"] = $_COOKIE['VIEW_MODE'];
}

// Ищем переопределение SEO от фильтра
$filter_url = $_SERVER['SCRIPT_URL'];
$element = \Bitrix\Iblock\ElementTable::getList(['filter' => ['IBLOCK_ID' => $FILTER_IBLOCK_ID, 'CODE' => $filter_url, 'ACTIVE' => 'Y'], 'select' => ['ID', 'DETAIL_TEXT']])->Fetch();
if ($element) {
    $ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($FILTER_IBLOCK_ID, $element['ID']);
    $values = $ipropElementValues->getValues();
    $arResult['PATH'][] = ['NAME' => $values['ELEMENT_PAGE_TITLE'], 'SECTION_PAGE_URL' => $filter_url];
    $arResult['DESCRIPTION'] = $element['DETAIL_TEXT'];
}
DJgeo::geoReplace($arResult['DESCRIPTION']);