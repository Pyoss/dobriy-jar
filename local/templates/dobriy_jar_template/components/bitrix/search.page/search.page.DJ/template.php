<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */


# ----------------------- ОСНОВНОЙ HTML ----------------------
?>
<div class="search-string">
    Найденые результаты по запросу: <span class="search-phrase"><?=$arResult['REQUEST']['QUERY']?></span>
</div>
<div class="found-sections">


</div>
<div class="catalog">
    <div class='catalog-control-panel'>
        <div class='price-view-panel'>
            <span>Сортировать по цене</span>
            <span class='catalog-sort price-descend' id="sort-price-descend"></span>
            <span class='catalog-sort price-ascend' id="sort-price-ascend"></span>
        </div>
        <div class='catalog-view-panel'>
            <span class='column-view-control' id='column-view'></span>
            <span class='row-view-control' id='row-view'></span>
        </div>
    </div>
    <div class='catalog-products-container row-view' id="catalog-view">
        <?
        foreach ($arResult["ITEMS"] as $ITEM){

            $APPLICATION->IncludeComponent(
                'bitrix:catalog.item',
                '.default',
                array(
                    'RESULT' => array(
                        'ITEM' => $ITEM
                    )
                ),
                $component,
                array('HIDE_ICONS' => 'Y')
            );
        }
        ?>
        <?php
        # ----------------------- НИЖНЯЯ НАВИГАЦИЯ ----------------------

        ?>
        <div data-pagination-num="<?=$navParams['NavNum']?>">
            <!-- pagination-container -->
            <?=$arResult['NAV_STRING']?>
            <!-- pagination-container -->
        </div>
    </div>
</div>