<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/bootstrap_v4/script.js");
Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/bootstrap_v4/style.css");
CJSCore::Init(array('clipboard', 'fx'));

Loc::loadMessages(__FILE__);

if (!empty($arResult['ERRORS']['FATAL'])) {
    foreach ($arResult['ERRORS']['FATAL'] as $code => $error) {
        if ($code !== $component::E_NOT_AUTHORIZED)
            ShowError($error);
    }
    $component = $this->__component;
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
    if (!count($arResult['ORDERS'])) {
        if ($_REQUEST["filter_history"] == 'Y') {
            if ($_REQUEST["show_canceled"] == 'Y') {
                ?>
                <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_CANCELED_ORDER') ?></h3>
                <?
            } else {
                ?>
                <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_HISTORY_ORDER_LIST') ?></h3>
                <?
            }
        } else {
            ?>
            <h3><?= Loc::getMessage('SPOL_TPL_EMPTY_ORDER_LIST') ?></h3>
            <?
        }
    }
    ?>
    <div class="order-choice">
        <?
        $nothing = !isset($_REQUEST["filter_history"]) && !isset($_REQUEST["show_all"]);
        $clearFromLink = array("filter_history", "filter_status", "show_all", "show_canceled");

        if ($nothing || $_REQUEST["filter_history"] == 'N') {
            ?>
            <a class="dj_link" href="<?= $APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false) ?>">
                > История заказов</a>
            <?
        }
        if ($_REQUEST["filter_history"] == 'Y') {
            ?>
            <a class="dj_link" href="<?= $APPLICATION->GetCurPageParam("", $clearFromLink, false) ?>">
                > Текущие заказы</a>
            <?

        }
        ?>
    </div>
    <?
    if (!count($arResult['ORDERS'])) {
        ?>
        <div class="row mb-3">
            <div class="col">
                <a href="<?= htmlspecialcharsbx($arParams['PATH_TO_CATALOG']) ?>"
                   class="mr-4"><?= Loc::getMessage('SPOL_TPL_LINK_TO_CATALOG') ?></a>
            </div>
        </div>
        <?
    }

    if ($_REQUEST["filter_history"] !== 'Y') {
        $paymentChangeData = array();
        $orderHeaderStatus = null;

        foreach ($arResult['ORDERS'] as $key => $order) {
            if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $_REQUEST["show_canceled"] !== 'Y') {
                $orderHeaderStatus = $order['ORDER']['STATUS_ID'];
                ?>
                <h1 class="sale-order-title">
                    Текущие заказы
                </h1>
                <?
            }
            ?>
            <div class="history-order">
                <? $arDetParams = array(
                    "PATH_TO_LIST" => $arResult["PATH_TO_ORDERS"],
                    "PATH_TO_CANCEL" => $arResult["PATH_TO_ORDER_CANCEL"],
                    "PATH_TO_COPY" => $arResult["PATH_TO_ORDER_COPY"],
                    "PATH_TO_PAYMENT" => $arParams["PATH_TO_PAYMENT"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "ID" => $order['ORDER']["ID"],
                    "ACTIVE_DATE_FORMAT" => $arParams["ACTIVE_DATE_FORMAT"],
                    "ALLOW_INNER" => $arParams["ALLOW_INNER"],
                    "ONLY_INNER_FULL" => $arParams["ONLY_INNER_FULL"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "RESTRICT_CHANGE_PAYSYSTEM" => $arParams["ORDER_RESTRICT_CHANGE_PAYSYSTEM"],
                    "REFRESH_PRICES" => $arParams["ORDER_REFRESH_PRICES"],
                    "DISALLOW_CANCEL" => $arParams["ORDER_DISALLOW_CANCEL"],
                    "HIDE_USER_INFO" => $arParams["ORDER_HIDE_USER_INFO"],
                    "AUTH_FORM_IN_TEMPLATE" => 'Y',
                    "CONTEXT_SITE_ID" => $arParams["CONTEXT_SITE_ID"],
                    "CUSTOM_SELECT_PROPS" => $arParams["CUSTOM_SELECT_PROPS"]
                );
                foreach ($arParams as $k => $val) {
                    if (mb_strpos($k, "PROP_") !== false)
                        $arDetParams[$k] = $val;
                }

                $APPLICATION->IncludeComponent(
                    "bitrix:sale.personal.order.detail",
                    "current_order",
                    $arDetParams,
                    $component
                ); ?>
            </div>
            <?
        }
    } else {
        $orderHeaderStatus = null;

        if ($_REQUEST["show_canceled"] === 'Y' && count($arResult['ORDERS'])) {
            ?>
            <div class="row mb-3">
                <div class="col">
                    <h2><?= Loc::getMessage('SPOL_TPL_ORDERS_CANCELED_HEADER') ?></h2>
                </div>
            </div>
            <?
        }

        foreach ($arResult['ORDERS'] as $key => $order) {
            if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $_REQUEST["show_canceled"] !== 'Y') {
                $orderHeaderStatus = $order['ORDER']['STATUS_ID'];
                ?>
                <h1 class="sale-order-title">
                    Отгруженные заказы
                </h1>
                <?
            }
            ?>
            <div class="history-order">
                <? $arDetParams = array(
                    "PATH_TO_LIST" => $arResult["PATH_TO_ORDERS"],
                    "PATH_TO_CANCEL" => $arResult["PATH_TO_ORDER_CANCEL"],
                    "PATH_TO_COPY" => $arResult["PATH_TO_ORDER_COPY"],
                    "PATH_TO_PAYMENT" => $arParams["PATH_TO_PAYMENT"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "ID" => $order['ORDER']["ID"],
                    "ACTIVE_DATE_FORMAT" => $arParams["ACTIVE_DATE_FORMAT"],
                    "ALLOW_INNER" => $arParams["ALLOW_INNER"],
                    "ONLY_INNER_FULL" => $arParams["ONLY_INNER_FULL"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "RESTRICT_CHANGE_PAYSYSTEM" => $arParams["ORDER_RESTRICT_CHANGE_PAYSYSTEM"],
                    "REFRESH_PRICES" => $arParams["ORDER_REFRESH_PRICES"],
                    "DISALLOW_CANCEL" => $arParams["ORDER_DISALLOW_CANCEL"],
                    "HIDE_USER_INFO" => $arParams["ORDER_HIDE_USER_INFO"],
                    "AUTH_FORM_IN_TEMPLATE" => 'Y',
                    "CONTEXT_SITE_ID" => $arParams["CONTEXT_SITE_ID"],
                    "CUSTOM_SELECT_PROPS" => $arParams["CUSTOM_SELECT_PROPS"]
                );
                foreach ($arParams as $k => $val) {
                    if (mb_strpos($k, "PROP_") !== false)
                        $arDetParams[$k] = $val;
                }

                $APPLICATION->IncludeComponent(
                    "bitrix:sale.personal.order.detail",
                    "history_order",
                    $arDetParams,
                    $component
                ); ?>
                <div class="history__repeat-order">
                    <a class="dj_link" href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"]) ?>"><?= Loc::getMessage('SPOL_TPL_REPEAT_ORDER') ?></a>
                </div>
            </div>
            <?
        }
    }

    echo $arResult["NAV_STRING"];

    if ($_REQUEST["filter_history"] !== 'Y') {
        $javascriptParams = array(
            "url" => CUtil::JSEscape($this->__component->GetPath() . '/ajax.php'),
            "templateFolder" => CUtil::JSEscape($templateFolder),
            "templateName" => $this->__component->GetTemplateName(),
            "paymentList" => $paymentChangeData,
            "returnUrl" => CUtil::JSEscape($arResult["RETURN_URL"]),
        );
        $javascriptParams = CUtil::PhpToJSObject($javascriptParams);
        ?>
        <script>
            BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
        </script>
        <?
    }
}
?>
