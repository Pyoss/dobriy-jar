<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$flying_basket = (boolean) $_GET['basket_html'];
if ($arParams['HEADER_INFO'] == 'Y'):?>
    <li style='display: flex;flex-flow: column nowrap;justify-content: center;align-items: center;' class="user-buttons--element cart" id="header-cart"><span class="inline-icon basket-icon"></span>

        <span class="basket-marker" id="basket-quantity"><?= $arResult['BASKET_ITEMS_COUNT'] ?: '' ?>
            </span>
        <span class="user-button--text desktop" style="color: white">Корзина</span>
    </li>
<?
else:
    if ($flying_basket): ?>
    <div class="cart-header">
        <div class="cart-title">
            Ваша корзина
        </div>
        <div class="cart-close popup_close no-select">
            X
        </div>
    </div>
    <?endif;
    if (!$arResult['BASKET_ITEMS_COUNT']):?>
        <div class="basket-empty center-content">Корзина пуста</div>
    <? else: ?>
        <div class="cart-container"<?=$flying_basket ? '' : ' id="order-cart"'?>>
            <? foreach ($arResult['ITEMS']['AnDelCanBuy'] as $product): ?>
                <div class="cart-row" data-product-id="<?= $product['PRODUCT_ID'] ?>">
                    <div class="cart-row--picture-wrapper">
                        <img class="cart-image" src="<?= $product["DETAIL_PICTURE_SRC_2X"] ?>">
                    </div>
                    <div class="cart-row--cart-info">
                        <div class="cart-row--specs">
                            <div class="cart-specs--article"><?= $product["PROPERTY_ARTNUMBER_VALUE"] ?></div>
                            <div class="cart-specs--name"><?= $product["NAME"] ?></div>

                        </div>
                        <div class="cart-row--price-block">
                            <span class="cart-row--price rub"><?= $product['PRICE_FORMATED'] ?></span>
                            <span class="cart-row--bprice rub"><?= ($product['DISCOUNT_PRICE'] != 0) ? $product['FULL_PRICE_FORMATED'] : "" ?></span>
                        </div>
                        <div class="cart-row--quantity-block" data-product-id="<?= $product['PRODUCT_ID'] ?>">
                    <span class="cart-decrement noselect<?= ($product['QUANTITY'] <= 1) ? ' inactive' : '' ?>"
                          data-product-id="<?= $product['PRODUCT_ID'] ?>">-</span>
                            <input class="cart-quantity"
                                   value="<?= $product['QUANTITY'] ?>"
                                   type="text"
                                   maxlength="2048"
                                   data-product-id="<?= $product['PRODUCT_ID'] ?>"
                            >
                            <span class="cart-increment noselect<?= ($product['QUANTITY'] >= 100) ? ' inactive' : '' ?>"
                                  data-product-id="<?= $product['PRODUCT_ID'] ?>">+</span>
                        </div>
                        <span class="cart-row--del-block trash-icon noselect"
                              data-product-id="<?= $product['PRODUCT_ID'] ?>">

                </span>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
        <? if ($flying_basket): ?>
        <div class="cart-footer">
            <div class="cart-footer--order-sum">
                <span>Итого:</span>
                <span class="cart-footer--order-price rub"><?= $arResult['allSum_FORMATED'] ?></span>
                <span class="cart-footer--order-bprice rub">
                    <?= ($arResult['DISCOUNT_PRICE_ALL'] != 0) ? $arResult['PRICE_WITHOUT_DISCOUNT'] : "" ?>
                </span>
            </div>
                <div class="cart-footer--shop-button">Продолжить покупки</div>
            <a href="/personal/order/make/" class="dj_link">
                <div class="cart-footer--order-button goal_zakazat">Заказать</div>
            </a>
        </div>
        <? endif; ?>
    <? endif; ?>
<?endif;
if ($arParams['HEADER_INFO'] !== 'Y' && !$flying_basket):
    ?>
    <script>
        setCartListeners({cart_id:'order-cart'}, BX.Sale.OrderAjaxComponent);
    </script>
<? endif; ?>