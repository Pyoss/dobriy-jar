<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 *
 *  _________________________________________________________________________
 * |	Attention!
 * |	The following comments are for system use
 * |	and are required for the component to work correctly in ajax mode:
 * |	<!-- items-container -->
 * |	<!-- pagination-container -->
 * |	<!-- component-end -->
 */

$this->setFrameMode(true);
# ----------------------- ПРЕДЗАГРУЗКА НАВИГАЦИИ ----------------------
if (!empty($arResult['NAV_RESULT']))
{
	$navParams =  array(
		'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
		'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
		'NavNum' => $arResult['NAV_RESULT']->NavNum
	);
}
else
{
	$navParams = array(
		'NavPageCount' => 1,
		'NavPageNomer' => 1,
		'NavNum' => $this->randString()
	);
}

# ----------------------- ПЕРЕМЕННЫЕ НАВИГАЦИИ ----------------------

$showTopPager = false;
$showBottomPager = false;
$showLazyLoad = false;

# ----------------------- ГЛАВНЫЕ НАСТРОЙКИ ----------------------

$generalParams = array(
	'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
	'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
	'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
	'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
	'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
	'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
	'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
	'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
	'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
	'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
	'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
	'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
	'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
	'COMPARE_PATH' => $arParams['COMPARE_PATH'],
	'COMPARE_NAME' => $arParams['COMPARE_NAME'],
	'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
	'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
	'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
	'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
	'~BASKET_URL' => $arParams['~BASKET_URL'],
	'~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
	'~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
	'~COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
	'~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
	'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
	'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
	'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
	'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
	'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
	'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
	'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE']
);

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($navParams['NavNum']));
$containerName = 'container-'.$navParams['NavNum'];


$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

$areaIds = array();
foreach ($arResult['ITEMS'] as $item)
{
    $uniqueId = $item['ID'].'_'.md5($this->randString().$component->getAction());
    $areaIds[$item['ID']] = $this->GetEditAreaId($uniqueId);
    $this->AddEditAction($uniqueId, $item['EDIT_LINK'], $elementEdit);
    $this->AddDeleteAction($uniqueId, $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
}

$SKU_PROP_DATA = array_values($arResult['SKU_PROPS'])[0];
# ----------------------- ОСНОВНОЙ HTML ----------------------
?>
<div class='catalog-ctrl'>
    <div class="catalog-ctrl__pagination" data-pagination-num="<?=$navParams['NavNum']?>">
        <!-- pagination-container -->
        <?=$arResult['NAV_STRING']?>
        <!-- pagination-container -->
    </div>
    <div class="catalog-ctrl__view">
        <div class='price-view'>
            <span class="catalog-ctrl__title">По цене:</span>
            <span class='price-view__descend' id="sort-price-descend"></span>
            <span class='price-view__ascend' id="sort-price-ascend"></span>
        </div>
        <div class='item-view'>
            <span class='item-view__column<?=($arParams['VIEW_MODE'] == 'column-view') ? ' active':''?>' id='column-view'></span>
            <span class='item-view__row<?=($arParams['VIEW_MODE'] == 'row-view') ? ' active':''?>' id='row-view'></span>
        </div>
        <div class='filter-view' style="display: none">
            <span class="catalog-ctrl__title">Фильтр:</span>
            <span class='filter-view__activate' id='activate-filter'></span>
       </div>
    </div>
</div>
<div class="catalog-flex-wrapper" id="catalog-view">
    <div class='catalog-products-container <?=($arParams['VIEW_MODE'])?>'>
        <?
;    foreach ($arResult["ITEMS"] as $ITEM){
        $APPLICATION->IncludeComponent(
            'bitrix:catalog.item',
            '.default',
            array(
                'RESULT' => array(
                    'ITEM' => $ITEM,
                    'AREA_ID' => $areaIds[$ITEM['ID']],
                ),
                'PARAMS' => $generalParams
                    + array('SKU_PROPS' => $arResult['SKU_PROPS'][$ITEM['IBLOCK_ID']])
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );
    }
    ?>
    </div>
    <div data-pagination-num="<?=$navParams['NavNum']?>">
        <!-- pagination-container -->
        <?=$arResult['NAV_STRING']?>
        <!-- pagination-container -->
    </div>
    <?php
    if ($arResult['SECTION_TEXT'])?>
    <div class="catalog-section-text">
        <?=$arResult['DESCRIPTION']?>
    </div>
    <div class="catalog-overlay loading-overlay" id="catalog-loading-overlay" style="display: none"></div>
</div>