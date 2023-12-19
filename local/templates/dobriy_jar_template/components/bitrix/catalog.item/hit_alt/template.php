<?php
$ITEM = $arResult['ITEM'];
$hasOffers = !!$ITEM['OFFERS'];
$is_available = $ITEM['CATALOG_QUANTITY'] > 0;
if ($hasOffers) {
    $is_available = true;
    $SKU_PROP_DATA = $arParams['SKU_PROPS'];
    $curOffer = $ITEM['OFFERS'][$ITEM['OFFERS_SELECTED']];
    $product_type = $ITEM['PROPERTIES']['PRODUCT_TYPE']['VALUE'];
    $curOffer['VIEW']['NAME'] = DJMain::replaceProductType($product_type, $curOffer['NAME']);
    $article = $curOffer['DISPLAY_PROPERTIES']['ARTNUMBER']['DISPLAY_VALUE'];
} else {
    $article = $ITEM['DISPLAY_PROPERTIES']['ARTNUMBER']['VALUE'];
}
$price = $curOffer ? $curOffer['ITEM_PRICES'][0]['PRINT_PRICE'] : $ITEM['ITEM_PRICES'][0]['PRINT_RATIO_PRICE'];
$bprice = $curOffer ? $curOffer['ITEM_PRICES'][0]['PRINT_BASE_PRICE'] : $ITEM['ITEM_PRICES'][0]['PRINT_BASE_PRICE'];

$percent = false;
if ($price < $bprice) {
    $percent = $curOffer ? $curOffer['ITEM_PRICES'][0]['PERCENT'] : $ITEM['ITEM_PRICES'][0]['PERCENT'];
}
?>
    <div class="product-element" id="<?= $arResult['AREA_ID'] ?>"
         <? if (!$is_available): ?>style="order:99"<?php endif; ?>
         data-product-id="<?= $ITEM['ID'] ?>" <?= $ITEM['IBLOCK_SECTION_ID'] ? 'data-section-id="' . $ITEM['IBLOCK_SECTION_ID'] . '"' : '' ?>>
        <a class="product-element--link" href="<?= $ITEM['URL_WO_PARAMS'] ?: $ITEM['DETAIL_PAGE_URL']; ?>">

            <div class="product-element--image-wrapper">
                <? if ($arResult['LOADING']): ?>
                    <div class="loading-overlay"></div>
                <? endif; ?>
                <img class="product-element--image"
                    <? if ($arResult['LOADING']): ?>
                        loading="lazy"
                        onload="this.parentNode.querySelector('.loading-overlay').classList.remove('loading-overlay')"
                    <? endif; ?>
                     src="<?= $ITEM['PICTURE']['SRC'] ?>">
            </div>

            <div class="product-element--info-wrapper">
                <div class="product-element--name-wrapper">
                    <div id="type-<?= $curOffer ? $curOffer['ID'] : $ITEM['ID'] ?>"
                         class="product-type"><?= $ITEM['PROPERTIES']['PRODUCT_TYPE']['VALUE'] ?></div>
                    <div id="name-<?= $curOffer ? $curOffer['ID'] : $ITEM['ID'] ?>"
                         class="product-name"><?= $curOffer ? $curOffer['VIEW']['NAME'] : $ITEM['VIEW']['NAME'] ?></div>

                </div>
            </div>
        </a>
    </div>
<?