<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

CJSCore::Init(array('clipboard', 'fx'));

$APPLICATION->SetTitle("");

if (!empty($arResult['ERRORS']['FATAL'])) {
    $component = $this->__component;
    foreach ($arResult['ERRORS']['FATAL'] as $code => $error) {
        if ($code !== $component::E_NOT_AUTHORIZED)
            ShowError($error);
    }

    if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])) {
        ?>
        <div class="row">
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="alert alert-danger"><?= $arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED] ?></div>
            </div>
            <? $authListGetParams = array(); ?>
            <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <? $APPLICATION->AuthForm('', false, false, 'N', false); ?>
            </div>
        </div>
        <?
    }
} else {
    if (!empty($arResult['ERRORS']['NONFATAL'])) {
        foreach ($arResult['ERRORS']['NONFATAL'] as $error) {
            ShowError($error);
        }
    }
    ?>
    <div class="history__order">
        <span class="history__order-date"><?= $arResult["DATE_INSERT_FORMATED"] ?></span><br>
        <span class="history__order-name">Заказ №<?= $arResult["ACCOUNT_NUMBER"] ?></span>
        <? foreach ($arResult['PAYMENT'] as $payment) {
            ?>
            <div class="history__payment-details">
                <?
                $paymentData[$payment['ACCOUNT_NUMBER']] = array(
                    "payment" => $payment['ACCOUNT_NUMBER'],
                    "order" => $arResult['ACCOUNT_NUMBER'],
                    "allow_inner" => $arParams['ALLOW_INNER'],
                    "only_inner_full" => $arParams['ONLY_INNER_FULL'],
                    "refresh_prices" => $arParams['REFRESH_PRICES'],
                    "path_to_payment" => $arParams['PATH_TO_PAYMENT']
                );
                ?>
                <span class="history__payment-title"><b>Оплата</b>: <?= $payment['PAY_SYSTEM_NAME'] ?></span>

            </div>
            <?
        }
        ?>

        <? if (count($arResult['SHIPMENT'])) {
            $address = '';
            foreach ($arResult['ORDER_PROPS'] as $order_prop) {
                if ($order_prop['ID'] == 7) {
                    $address = $order_prop['VALUE'];
                }
            }
            ?>

            <? foreach ($arResult['SHIPMENT'] as $shipment) {
                ?>


                <div class="history__delivery-details">

                    <? if ($shipment["DELIVERY_NAME"] <> '') {
                        ?>
                        <div
                                class="history__delivery-name">
                            <b>Доставка</b>: <?= htmlspecialcharsbx($shipment["DELIVERY_NAME"]) ?></div>
                        <?
                    }
                    ?>


                    <? if ($shipment['TRACKING_URL'] <> '') {
                        ?>
                        <div
                                class="mb-2 sale-order-detail-payment-options-shipment-button-container">
                            <a href="" onclick="return false"
                               class="sale-order-detail-payment-options-shipment-button-element"
                               href="<?= $shipment['TRACKING_URL'] ?>"><?= Loc::getMessage('SPOD_ORDER_CHECK_TRACKING') ?></a>
                        </div>
                        <?
                    }
                    ?>

                    <? if ($address <> '') {
                        ?>

                        <div
                                class="history__delivery-address"><?= htmlspecialcharsbx($address) ?></div>
                        <?
                    } ?>
                </div>

                <?
            }
            ?>
            <?
        }
        ?>
        <div class="history__products">
            <?
            foreach ($arResult['BASKET'] as $basketItem) {
                ?>
                <div class="history__product">
                    <a href="<?= $basketItem['DETAIL_PAGE_URL'] ?>">
                        <div class="history__product-image">
                            <?
                            if ($basketItem['PICTURE']['SRC'] <> '') {
                                $imageSrc = $basketItem['PICTURE']['SRC'];
                            } else {
                                $imageSrc = DJMain::IMAGE_TEMPLATE_SRC;
                            }
                            ?>
                            <img src="<?= $imageSrc ?>" alt="">
                        </div>
                        <div class="history__product-type">
                            <?= $basketItem['PRODUCT_TYPE'] ?>
                        </div>
                        <div class="history__product-name"><?= htmlspecialcharsbx($basketItem['NAME']) ?></div>
                        <div class="history__product-article">
                            <?= $basketItem['ARTICLE'] ?>
                        </div>
                        <div class="history__product-price">
                            <div class="history__price-bought">
                                <?= $basketItem['PRICE_FORMATED'] ?>
                            </div>
                            <div class="history__product-quantity">

                                <?= $basketItem['QUANTITY'] ?>&nbsp;
                                <?
                                if ($basketItem['MEASURE_NAME'] <> '') {
                                    echo htmlspecialcharsbx($basketItem['MEASURE_NAME']);
                                } else {
                                    echo 'шт';
                                }
                                ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?
            }
            ?>
        </div>
        <div class="history__total">
            <? if (floatval($arResult["ORDER_WEIGHT"])) { ?>
                <div class="history__order-weight">
                    <div class="history__total-title">Вес заказа:</div>
                    <div class="history__total-value"><?= $arResult['ORDER_WEIGHT_FORMATED'] ?></div>
                </div>
            <? }
            if ($arResult['PRODUCT_SUM_FORMATED'] != $arResult['PRICE_FORMATED'] && !empty($arResult['PRODUCT_SUM_FORMATED'])) { ?>
                <div class="history__product-sum">
                    <div class="history__total-title">Сумма товаров:</div>
                    <div class="history__total-value"><?= $arResult['PRODUCT_SUM_FORMATED'] ?></div>
                </div>
            <? }
            if ($arResult["PRICE_DELIVERY_FORMATED"] <> '') { ?>
                <div class="history__product-delivery">
                    <div class="history__total-title">Стоимость доставки:</div>
                    <div class="history__total-value"><?= $arResult['PRICE_DELIVERY_FORMATED'] ?></div>
                </div>
            <? }

            if ((float)$arResult["TAX_VALUE"] > 0) { ?>
                <div class="history__product-tax">
                <div class="history__total-title">НДС:</div>
                <div class="history__total-value"><?= $arResult['TAX_VALUE_FORMATED'] ?></div>
                </div><? } ?>
            <div class="history__total-sum">
                <div class="history__total-title">Сумма заказа:</div>
                <div class="history__total-value"><?= $arResult['PRICE_FORMATED'] ?></div>
            </div>
        </div>

    </div>
    <?
}
?>

