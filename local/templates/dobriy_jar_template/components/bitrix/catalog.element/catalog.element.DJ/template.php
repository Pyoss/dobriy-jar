<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */
$hasGallery = count($arResult['VIEW']['GALLERY']) > 1;
$APPLICATION->SetTitle($arResult['META']['TITLE']);
$geo = new DJgeo();
$is_available = $arResult['VIEW']['AVAILABLE'];
$hasOffers = !!$arResult['OFFERS'];
$video = $arResult['PROPERTIES']['VIDEO']['VALUE'];
if ($hasOffers) {
    $SKU_PROP_DATA = $arResult['SKU_PROPS'];
    $curOffer = $arResult['CURRENT_OFFER'];
    $article = $curOffer['DISPLAY_PROPERTIES']['ARTNUMBER']['DISPLAY_VALUE'];
    $is_available = true;
}
?>
<div class="product-wrapper" id="<?= $arResult['ID'] ?>" data-product-id="<?= $arResult['ID'] ?>">
    <div class="detail-product-top">
        <div class="image-wrapper<?= $hasGallery ? ' ' : ' center ' ?>">
            <? if ($hasGallery): ?>
                <div class="image-gallery">
                    <div class="gallery-container" id="gallery-image-container" data-index="0">
                        <? foreach ($arResult['VIEW']['GALLERY'] as $arImage): ?>
                            <div class="gallery-preview">
                                <img loading="lazy" src="<?= $arImage['resize']['src'] ?>"
                                     data-src="<?= $arImage['src'] ?>"
                                     data-source="<?= $arImage['source'] ?>">
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>
                <? if (count($arResult['VIEW']['GALLERY']) > 4): ?>
                    <div class="control arrow-up" id="gallery-arrow-up"></div>
                    <div class="control arrow-down" id="gallery-arrow-down"></div>
                <? endif; ?>
            <? endif; ?>
            <div class="image-container" id="detail-image-container">
                <img loading="lazy" class="product-image fullscreen-option"

                     src="<?= $arResult['VIEW']['GALLERY'][0]['src'] ?>">
            </div>
        </div>
        <div class="offer-wrapper">
            <? if ($arResult['BRAND']): ?>
                <a href="<?= 'https://' . $_SERVER['SERVER_NAME'] . '/brands/' . $arResult['BRAND']['CODE'] . '/' ?>">
                    <div class="product-brand">
                        <?= $arResult['BRAND']['PREVIEW_PICTURE'] ?
                            '<img src=' . $arResult["BRAND"]["PREVIEW_PICTURE"] . '>' :
                            $arResult['BRAND']['NAME'] ?>
                    </div>
                </a>
            <? endif; ?>
            <? if ($arResult['PROPERTIES']['PRODUCT_TYPE']['VALUE']): ?>
                <div id="type-<?= $arResult['VIEW']['ID'] ?>"
                     class="product-type"><?= $arResult['PROPERTIES']['PRODUCT_TYPE']['VALUE'] ?></div>
            <? endif; ?>
            <div id="name-<?= $arResult['VIEW']['ID'] ?>" class="product-name"><?= $arResult['VIEW']['NAME'] ?></div>
            <div class="article">Артикул: <span
                        id="article-<?= $arResult['VIEW']['ID'] ?>"><?= $arResult['VIEW']['ARTNUMBER'] ?></span></div>

            <div class="offer-sku noselect">
                <? if ($arResult['OFFERS']):
                    // ----------------------------------- Переключение предложений -------------------------------- //
                    foreach ($arResult['OFFERS_PROP'] as $PROP_CODE => $PROP_STATUS):
                        ?>
                        <div class="sku-prop-row" data-propcode="<?= $SKU_PROP_DATA[$PROP_CODE]['ID'] ?>">
                            <div class="sku-row-name"><?= $SKU_PROP_DATA[$PROP_CODE]['NAME'] ?></div>
                            <? foreach ($SKU_PROP_DATA[$PROP_CODE]['VALUES'] as $arOfferPropValueID => $arOfferPropValue):
                                if ($arOfferPropValue['NA']) {
                                    continue;
                                }
                                $thisProps = $curOffer['TREE'];
                                $thisProps['PROP_' . $SKU_PROP_DATA[$PROP_CODE]['ID']] = $arOfferPropValue['ID'];
                                $current = $curOffer['TREE']['PROP_' . $SKU_PROP_DATA[$PROP_CODE]['ID']] == $arOfferPropValue['ID'] ? ' current' : '';
                                $OFFER_CODE = '';
                                foreach ($thisProps as $prop => $value) {
                                    $OFFER_CODE .= (explode('_', $prop)[1]) . '_' . $value . ':';
                                }

                                $thisSkuId = $arResult['JS_OFFERS_MAP'][$OFFER_CODE];
                                $inactive = !$thisSkuId ? ' inactive' : '';
                                ?><span class="sku-prop<?= $current ?><?= $inactive ?>"
                                        data-propid="<?= $arOfferPropValue['ID'] ?>"
                                        data-product-id="<?= $arResult['ID'] ?>"
                                        data-propcode="<?= $SKU_PROP_DATA[$PROP_CODE]['ID'] ?>">
                                <?= $arOfferPropValue['NAME'] ?></span>
                            <? endforeach; ?>
                        </div>
                    <?endforeach;
                endif;
                // --------------------------------------------------------------------------------------------- //
                ?>
            </div>
            <? if ($arResult['PROPERTIES']['QUALITIES']['VALUE']): ?>
                <div class="product-qual">
                    <span class="prop-title">Особенности:</span>
                    <span class="prop-anchor"><a class="dj_link"
                                                 href="#char-tab-title"
                                                 onclick="changeTab('char-tab-title')">Все особенности</a></span>
                    <? foreach ($arResult['PROPERTIES']['QUALITIES']['VALUE'] as $quality): ?>
                        <div class="prop-row">
                            <div class="prop-name"><?= $quality ?></div>
                            <div class="prop-space"></div>
                        </div>
                    <? endforeach ?>
                </div>
            <?endif;
            // --------------------------------------------------------------------------------------------- //
            ?>
            <?php
            if ($hasOffers):?>
                <json>
                    <?= json_encode($arResult['JS_OFFERS_MAP']) ?>
                </json>
            <?php endif; ?>
        </div>
        <div class="order-wrapper">
            <div class="order-blocks">
                <? if ($is_available): ?>
                    <div class="price-block">
                        <? /* if ($arResult['VIEW']['PERCENT']):?>
                    <div class="item-discount">
                        -<?=$arResult['VIEW']['PERCENT']?>%

                    </div>
                    <? endif;*/ ?>
                        <span id="price-<?= $arResult['VIEW']['ID'] ?>"
                              class="price rub"><?= $arResult['VIEW']['PRICE'] ?></span>
                        <span class="base-price rub desktop"><?= $arResult['VIEW']['DISCOUNT'] ? $arResult['VIEW']['BASE_PRICE'] : "" ?>
                    </span>
                    </div>
                <? else: ?>
                    <div class="price-block--unavailable">
                        <? /* if ($arResult['VIEW']['PERCENT']):?>
                    <div class="item-discount">
                        -<?=$arResult['VIEW']['PERCENT']?>%

                    </div>
                    <? endif;*/ ?>
                        <span class="price">Нет в наличии
                    </span>
                    </div>
                <? endif; ?>
                <div class="order-block">
                    <div class="order-block--title">Рассрочка</div>
                    <div class="order-block--text">Платеж от <?= floor((int)$arResult['VIEW']['RAW_PRICE'] / 6) ?>
                        руб/мес
                    </div>
                    <div class="order-block--decor">Покупка без переплат</div>
                </div>
                <? /*
                <div class="order-block">
                    <div class="order-block--title">Условия покупки</div>
                    <div class="order-block--text">В наличии в 5 магазинах в вашем регионе</div>
                    <div class="order-block--text">Доставка со склада</div>
                </div>
                */ ?>
                <div class="action-block">
                    <? if ($is_available): ?>
                        <div class="cart-action basket-add goal_basket_add"
                             data-product-id="<?= $arResult['VIEW']['ID'] ?>">В корзину
                        </div>
                    <? else: ?>
                        <div class="cart-action--unavailable">В корзину</div>
                    <? endif; ?>
                </div>
                <div class="cart-bought<?= ($arResult['VIEW']['BASKET']['QUANTITY']) ? ' visible' : '' ?>">
                    В корзине <?= ($arResult['VIEW']['BASKET']['QUANTITY']) ?> шт. товара
                </div>
            </div>
        </div>
    </div>
    <ul class="tab-switcher" id="tab-switcher">
        <li class="tab-title description active" id='desc-tab-title' onclick=changeTab(this.id)>Описание</li>
        <li class="tab-title characteristics" id='char-tab-title' onclick=changeTab(this.id)>Характеристики</li>
        <? if ($video): ?>
            <li class="tab-title video" id='video-tab-title' onclick=changeTab(this.id)>Видео</li>
        <? endif; ?>
        <? if ($arResult['COMPLECTATION']): ?>
            <li class="tab-title package" id='package-tab-title' onclick=changeTab(this.id)>Комплектация</li>
        <? endif; ?>
        <? if ($arResult['PROPERTIES']['RECOMMEND']['VALUE']): ?>
            <li class="tab-title additions" id='additions-tab-title' onclick=changeTab(this.id)>С этим товаром
                покупают
            </li>
        <? endif; ?>
        <? if ($arResult['REVIEWS']): ?>
            <li class="tab-title reviews" id='reviews-tab-title' onclick=changeTab(this.id)>Отзывы</li>
        <? endif; ?> <? if ($arResult['QUESTIONS']): ?>
            <li class="tab-title questions" id='questions-tab-title' onclick=changeTab(this.id)>Вопрос\Ответ</li>
        <? endif; ?>

    </ul>
    <div class="product-tab description active mobile-active" id="desc-tab">
        <div class="mobile-tab">
            <span class="mobile-tab--header">Описание</span>
            <span class="mobile-arrow"></span>
        </div>
        <div class="tab-content">
            <?= $arResult['DETAIL_TEXT'] ?>
        </div>
    </div>
    <div class="product-tab characteristics" id="char-tab">
        <div class="mobile-tab">
            <span class="mobile-tab--header">Характеристики</span>
            <span class="mobile-arrow"></span>
        </div>
        <div class="tab-content">
            <div class="char-wrapper">
                <div class="char-list">
                    <? foreach ($arResult['DISPLAY_PROPERTIES'] as $property):
                        ?>
                        <div class="prop-row">
                            <div class="prop-name"><?= $property['NAME'] ?>:</div>
                            <div class="prop-space"></div>
                            <div class="prop-value"><?= is_array($property['DISPLAY_VALUE']) ? implode(';<br>',
                                    $property['DISPLAY_VALUE']) : $property['DISPLAY_VALUE'] ?></div>
                        </div>
                    <? endforeach; ?>
                </div>
                <? if ($arResult['VIEW']['DISPLAY_PROPERTIES']): ?>
                    <div class="char-list">
                        <? foreach ($arResult['VIEW']['DISPLAY_PROPERTIES'] as $property):
                            ?>
                            <div class="prop-row">
                                <div class="prop-name"><?= $property['NAME'] ?>:</div>
                                <div class="prop-space"></div>
                                <div class="prop-value"><?= is_array($property['DISPLAY_VALUE']) ? implode(';<br>',
                                        $property['DISPLAY_VALUE']) : $property['DISPLAY_VALUE'] ?></div>
                            </div>
                        <? endforeach; ?>
                    </div>
                <? endif ?>
                <!--
                <div class="prop-picture">
                    <img class="fullscreen-option" loading="lazy" src="<?= $arResult['PROP_PICT'] ?>"
                         data-src="<?= $arResult['PROP_PICT'] ?>">
                </div>
                --!>
            </div>
        </div>
    </div>
    <?
    if ($video): ?>
        <div class="product-tab video" id="video-tab">
            <div class="mobile-tab">
                <span class="mobile-tab--header">Видео</span>
                <span class="mobile-arrow"></span>
            </div>
            <div class="tab-content">
                <? foreach ($video as $video_link): ?>
                    <div class="desc-block video">
                        <div class="video-wrapper">
                            <iframe
                                    src="https://www.youtube.com/embed/<?= $video_link ?>">
                            </iframe>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    <? endif; ?>
    <? if ($arResult['PROPERTIES']['RECOMMEND']['VALUE']): ?>
        <div class="product-tab additions" id="additions-tab">
            <div class="mobile-tab">
                <span class="mobile-tab--header">С этим товаром покупают</span>
                <span class="mobile-arrow"></span>
            </div>
            <div class="tab-content">

                <?

                global $arrFilterTop;
                if (!is_array($arrFilterTop))
                    $arrFilterTop = array();
                $arrFilterTop['ID'] = $arResult['PROPERTIES']['RECOMMEND']['VALUE'];   // ID нужных элементов

                $APPLICATION->IncludeComponent(
                    "bitrix:catalog.top",
                    "",
                    array(
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "ELEMENT_SORT_FIELD" => "sort",//$arParams["ELEMENT_SORT_FIELD"],
                        "ELEMENT_SORT_ORDER" => "asc",//$arParams["ELEMENT_SORT_ORDER"],
                        "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                        "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                        "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
                        "BASKET_URL" => $arParams["BASKET_URL"],
                        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                        "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                        "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                        "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                        "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                        "DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
                        "SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],
                        "SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
                        "LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
                        "PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
                        "PRICE_CODE" => $arParams['PRICE_CODE'],
                        "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                        "PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
                        "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                        "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                        "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                        "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "CACHE_FILTER" => 'Y',
                        "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                        "OFFERS_FIELD_CODE" => ['NAME', 'ID'],
                        "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                        "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                        "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                        "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                        "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                        "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
                        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                        'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
                        'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
                        'ROTATE_TIMER' => (isset($arParams['TOP_ROTATE_TIMER']) ? $arParams['TOP_ROTATE_TIMER'] : ''),
                        'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                        'LABEL_PROP' => $arParams['LABEL_PROP'],
                        'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                        'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                        'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                        'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                        'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                        'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                        'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams['SHOW_DISCOUNT_PERCENT_NUMBER'],
                        'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                        "FILTER_NAME" => "arrFilterTop",
                        'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
                        'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
                        'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
                        'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
                        'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
                        'ADD_TO_BASKET_ACTION' => $basketAction,
                        'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                        'COMPARE_PATH' => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['compare'],
                    ),
                    $component, array("HIDE_ICONS" => "Y")
                ); ?>
            </div>
        </div>
    <? endif; ?>
    <? if ($arResult['COMPLECTATION']): ?>
        <div class="product-tab package" id="package-tab">
            <div class="mobile-tab">
                <span class="mobile-tab--header">Комплектация</span>
                <span class="mobile-arrow"></span>
            </div>
            <div class="tab-content">
                <? foreach ($arResult['COMPLECTATION'] as $comp_block_name => $comp_block_array):
                    ?>
                    <? if ($comp_block_name): ?>
                    <h5 class="package-block__title"><?= $comp_block_name ?></h5><br>
                <? endif; ?>
                    <ol class="package-block">
                        <? foreach ($comp_block_array as $comp_item): ?>
                            <? if ($comp_item['active'] == 'Y'): ?>
                                <a href="/catalog/<?= $comp_item['code'] ?>/" target="_blank">
                            <? endif; ?>
                            <li class="package-item">
                                <div class="package-item__imgwrap">
                                    <img class="package-item__img" src="<?= $comp_item['img_src'] ?>">
                                </div>
                                <div class="package-item__name"><?= $comp_item['name'] ?></div>
                            </li>
                            <? if ($comp_item['active'] == 'Y'): ?>
                                </a>
                            <? endif; ?>
                        <? endforeach; ?>
                    </ol>
                <? endforeach; ?>
            </div>
        </div>
    <? endif; ?>
    <? if ($arResult['REVIEWS']): ?>
        <div class="product-tab reviews" id="reviews-tab">
            <div class="mobile-tab">
                <span class="mobile-tab--header">Отзывы</span>
                <span class="mobile-arrow"></span>
            </div>
            <div class="reviews__content tab-content">
                <button class="reviews__button desc-action-button contact-popup">ОСТАВИТЬ ОТЗЫВ</button>
                <? foreach ($arResult['REVIEWS'] as $REVIEW): ?>
                    <div class="review">
                        <div class="review__header">
                            <div class="review__avatar"><?= substr($REVIEW['PROPERTY_NAME_VALUE'], 0, 1); ?></div>
                            <div class="review__name"><?= $REVIEW['PROPERTY_NAME_VALUE'] ?></div>
                            <div class="review__stars rating__stars">
                                <? for ($i = 0; $i < 5 && $i < (int)$REVIEW['PROPERTY_STARS_VALUE']; $i++): ?>
                                    <span class="rating__star"></span>
                                <? endfor; ?>
                            </div>
                        </div>
                        <? if ($REVIEW['PROPERTY_STRENGTH_VALUE']['TEXT']): ?>
                            <div class="reviews__block">
                                <h6 class="reviews__block-title">
                                    Достоинства
                                </h6>
                                <p class="reviews__block-text">
                                    <?= $REVIEW['PROPERTY_STRENGTH_VALUE']['TEXT'] ?>
                                </p>
                            </div>
                        <? endif; ?>
                        <? if ($REVIEW['PROPERTY_WEAKNESS_VALUE']['TEXT']): ?>
                            <div class="reviews__block">
                                <h6 class="reviews__block-title">
                                    Недостатки
                                </h6>
                                <p class="reviews__block-text">
                                    <?= $REVIEW['PROPERTY_WEAKNESS_VALUE']['TEXT'] ?>
                                </p>
                            </div>
                        <? endif; ?>
                        <? if ($REVIEW['PROPERTY_COMMENT_VALUE']['TEXT']): ?>
                            <div class="reviews__block">
                                <h6 class="reviews__block-title">
                                    Комментарий
                                </h6>
                                <p class="reviews__block-text">
                                    <?= $REVIEW['PROPERTY_COMMENT_VALUE']['TEXT'] ?>
                                </p>
                            </div>
                        <? endif; ?>

                        <? if ($REVIEW['GALLERY']): ?>
                            <div class="reviews__block reviews__block--image">
                                <?foreach ($REVIEW['GALLERY'] as $PICTURE):?>
                                    <img src="<?=$PICTURE['auto']?>" class="fullscreen-option">
                                <?endforeach;?>
                            </div>
                        <? endif; ?>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    <? endif; ?>
    <? if ($arResult['QUESTIONS']): ?>
        <div class="product-tab questions" id="questions-tab">
            <div class="mobile-tab">
                <span class="mobile-tab--header">Вопрос\Ответ</span>
                <span class="mobile-arrow"></span>
            </div>
            <div class="questions__content tab-content">
                <button class="questions__button desc-action-button contact-popup">ЗАДАТЬ ВОПРОС</button>
                <? foreach ($arResult['QUESTIONS'] as $QUESTION): ?>
                    <div class="question">
                        <div class="question__question">
                            <img loading="lazy" src="/images/svg/profile.svg">
                            <p><?= $QUESTION['PROPERTY_QUESTION_VALUE']['TEXT'] ?></p>
                        </div>
                        <div class="question__answer">
                            <img loading="lazy" class="fullscreen-option" src="/upload/images/png/favicon.jpg">
                            <p><?= $QUESTION['PROPERTY_ANSWER_VALUE']['TEXT'] ?></p>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    <? endif; ?>
</div>

