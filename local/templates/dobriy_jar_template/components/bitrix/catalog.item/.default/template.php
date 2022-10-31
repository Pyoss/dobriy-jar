<?php
$ITEM = $arResult['ITEM'];
$hasOffers = !!$ITEM['OFFERS'];
$is_available = $ITEM['CATALOG_QUANTITY'] > 0;
if($hasOffers) {
    $is_available = true;
    $SKU_PROP_DATA = $arParams['SKU_PROPS'];
    $curOffer = $ITEM['OFFERS'][$ITEM['OFFERS_SELECTED']];
    $product_type = $ITEM['PROPERTIES']['PRODUCT_TYPE']['VALUE'];
    $curOffer['VIEW']['NAME'] = DJMain::replaceProductType($product_type, $curOffer['NAME']);
    $article = $curOffer['DISPLAY_PROPERTIES']['ARTNUMBER']['DISPLAY_VALUE'];
} else {
    $article = $ITEM['DISPLAY_PROPERTIES']['ARTNUMBER']['VALUE'];
}
DJMain::consoleString($arResult['ITEM']);
$price = $curOffer ? $curOffer['ITEM_PRICES'][0]['PRINT_PRICE'] : $ITEM['ITEM_PRICES'][0]['PRINT_RATIO_PRICE'];
$bprice = $curOffer ? $curOffer['ITEM_PRICES'][0]['PRINT_BASE_PRICE'] : $ITEM['ITEM_PRICES'][0]['PRINT_BASE_PRICE'];
$percent = false;
if ($price < $bprice){
    $percent = $curOffer ? $curOffer['ITEM_PRICES'][0]['PERCENT'] : $ITEM['ITEM_PRICES'][0]['PERCENT'];
}
if ($is_available){
    ?><div class="product-element" id="<?=$arResult['AREA_ID']?>" data-product-id="<?=$ITEM['ID']?>" <?= $ITEM['IBLOCK_SECTION_ID'] ? 'data-section-id="'.$ITEM['IBLOCK_SECTION_ID'].'"' : ''?>>
    <a class="product-element--link" href="<?=$ITEM['URL_WO_PARAMS'] ? : $ITEM['DETAIL_PAGE_URL'];?>">

        <div class="product-element--image-wrapper">
            <? if($arResult['LOADING']):?>
                <div class="loading-overlay"></div>
            <? endif;?>
            <img class="product-element--image"
                <? if($arResult['LOADING']):?>
                    loading="lazy"
                    onload="this.parentNode.querySelector('.loading-overlay').classList.remove('loading-overlay')"
                <?endif;?>
                 src="<?=$ITEM['PICTURE']['SRC']?>">
        </div>
        <div class="product-element--info-wrapper">
            <div class="product-element--price-block">
                <span id="price-<?=$curOffer ? $curOffer['ID'] : $ITEM['ID']?>" class="price"><?=$price?></span>

                <span class="base-price <?= $price < $bprice ? '' : 'hidden'?>"><?=$bprice?></span>
            </div>
            <div class="product-element--name-wrapper">
                <div id="type-<?=$curOffer ? $curOffer['ID'] : $ITEM['ID']?>" class="product-type"><?=$ITEM['PROPERTIES']['PRODUCT_TYPE']['VALUE']?></div>
                <div id="name-<?=$curOffer ? $curOffer['ID'] : $ITEM['ID']?>" class="product-name"><?=$curOffer ? $curOffer['VIEW']['NAME'] : $ITEM['VIEW']['NAME']?></div>
                <div class="product-element--article article">Артикул: <span id="article-<?=$curOffer ? $curOffer['ID'] : $ITEM['ID']?>"><?=$article?></span></div>
            </div>
        </div><?if ($percent):?>
            <div class="item-discount"> -<?=$percent?>%</div>
        <?endif?>
    </a>
    <div class="offer-sku noselect desktop">
        <?if ($ITEM['OFFERS']):
            // ----------------------------------- Переключение предложений -------------------------------- //
            foreach ($ITEM['OFFERS_PROP'] as $PROP_CODE => $PROP_STATUS):
                ?>
                <div class="sku-prop-row" data-propcode="<?=$SKU_PROP_DATA[$PROP_CODE]['ID']?>">
                    <?foreach ($SKU_PROP_DATA[$PROP_CODE]['VALUES'] as $arOfferPropValueID => $arOfferPropValue):
                        if (!$arOfferPropValue['NA'] && $ITEM['SKU_TREE_VALUES'][$SKU_PROP_DATA[$PROP_CODE]['ID']][$arOfferPropValueID]):
                            $thisProps = $curOffer['TREE'];
                            $thisProps['PROP_' . $SKU_PROP_DATA[$PROP_CODE]['ID']] = $arOfferPropValue['ID'];
                            $current = $curOffer['TREE']['PROP_' .$SKU_PROP_DATA[$PROP_CODE]['ID']] == $arOfferPropValue['ID'] ?  ' current' : '';
                            $OFFER_CODE = '';
                            foreach($thisProps as $prop => $value){
                                $OFFER_CODE .= (explode('_', $prop)[1]) . '_' . $value . ':';
                            }

                            $thisSkuId = $arResult['JS_OFFERS_MAP'][$OFFER_CODE];
                            $inactive = ! $thisSkuId ? ' inactive' : '';
                            ?><span class="sku-prop<?=$current?><?=$inactive?>"
                                    data-propid="<?=$arOfferPropValue['ID']?>"
                                    data-product-id="<?=$ITEM['ID']?>"
                                    data-propcode="<?=$SKU_PROP_DATA[$PROP_CODE]['ID']?>">
                            <?=$arOfferPropValue['NAME']?></span>
                        <?endif;
                    endforeach;?>
                </div>
            <?endforeach;
        endif;
        // --------------------------------------------------------------------------------------------- //
        ?>
    </div>
    <div class="product-element--drop desktop">
        <div class="product-element--action-wrapper">
            <span class="product-element--basket-button basket-add" data-product-id="<?=$curOffer ? $curOffer['ID'] : $ITEM['ID']?>">В корзину</span>
        </div>
    </div>
    <div class="product-element--drop mobile">
        <div class="product-element--action-wrapper">
            <span class="product-element--basket-button">Подробнее</span>
        </div>
    </div>
    <?php if($hasOffers):?>
        <json>
            <?=json_encode($arResult['JS_OFFERS_MAP'])?>
        </json>
    <?php endif;?>
    </div>
<?}