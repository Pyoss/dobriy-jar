<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 * @var $USER CUser
 * @var $component SaleOrderAjax
 */

$context = Main\Application::getInstance()->getContext();
$request = $context->getRequest();
$server = $context->getServer();


$arParams['ALLOW_USER_PROFILES'] = $arParams['ALLOW_USER_PROFILES'] === 'Y' ? 'Y' : 'N';
$arParams['SKIP_USELESS_BLOCK'] = $arParams['SKIP_USELESS_BLOCK'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['SHOW_ORDER_BUTTON'])) {
    $arParams['SHOW_ORDER_BUTTON'] = 'final_step';
}

$arParams['SHOW_TOTAL_ORDER_BUTTON'] = $arParams['SHOW_TOTAL_ORDER_BUTTON'] === 'Y' ? 'Y' : 'N';
$arParams['SHOW_PAY_SYSTEM_LIST_NAMES'] = $arParams['SHOW_PAY_SYSTEM_LIST_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_PAY_SYSTEM_INFO_NAME'] = $arParams['SHOW_PAY_SYSTEM_INFO_NAME'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_LIST_NAMES'] = $arParams['SHOW_DELIVERY_LIST_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_INFO_NAME'] = $arParams['SHOW_DELIVERY_INFO_NAME'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_DELIVERY_PARENT_NAMES'] = $arParams['SHOW_DELIVERY_PARENT_NAMES'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_STORES_IMAGES'] = $arParams['SHOW_STORES_IMAGES'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['BASKET_POSITION'])) {
    $arParams['BASKET_POSITION'] = 'after';
}

$arParams['SHOW_BASKET_HEADERS'] = $arParams['SHOW_BASKET_HEADERS'] === 'Y' ? 'Y' : 'N';
$arParams['DELIVERY_FADE_EXTRA_SERVICES'] = $arParams['DELIVERY_FADE_EXTRA_SERVICES'] === 'Y' ? 'Y' : 'N';
$arParams['SHOW_COUPONS_BASKET'] = $arParams['SHOW_COUPONS_BASKET'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_COUPONS_DELIVERY'] = $arParams['SHOW_COUPONS_DELIVERY'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_COUPONS_PAY_SYSTEM'] = $arParams['SHOW_COUPONS_PAY_SYSTEM'] === 'Y' ? 'Y' : 'N';
$arParams['SHOW_NEAREST_PICKUP'] = $arParams['SHOW_NEAREST_PICKUP'] === 'Y' ? 'Y' : 'N';
$arParams['DELIVERIES_PER_PAGE'] = isset($arParams['DELIVERIES_PER_PAGE']) ? intval($arParams['DELIVERIES_PER_PAGE']) : 9;
$arParams['PAY_SYSTEMS_PER_PAGE'] = isset($arParams['PAY_SYSTEMS_PER_PAGE']) ? intval($arParams['PAY_SYSTEMS_PER_PAGE']) : 9;
$arParams['PICKUPS_PER_PAGE'] = isset($arParams['PICKUPS_PER_PAGE']) ? intval($arParams['PICKUPS_PER_PAGE']) : 5;
$arParams['SHOW_PICKUP_MAP'] = $arParams['SHOW_PICKUP_MAP'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_MAP_IN_PROPS'] = $arParams['SHOW_MAP_IN_PROPS'] === 'Y' ? 'Y' : 'N';
$arParams['USE_YM_GOALS'] = $arParams['USE_YM_GOALS'] === 'Y' ? 'Y' : 'N';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

$useDefaultMessages = !isset($arParams['USE_CUSTOM_MAIN_MESSAGES']) || $arParams['USE_CUSTOM_MAIN_MESSAGES'] != 'Y';

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_BLOCK_NAME'])) {
    $arParams['MESS_AUTH_BLOCK_NAME'] = Loc::getMessage('AUTH_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REG_BLOCK_NAME'])) {
    $arParams['MESS_REG_BLOCK_NAME'] = Loc::getMessage('REG_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_BASKET_BLOCK_NAME'])) {
    $arParams['MESS_BASKET_BLOCK_NAME'] = Loc::getMessage('BASKET_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGION_BLOCK_NAME'])) {
    $arParams['MESS_REGION_BLOCK_NAME'] = Loc::getMessage('REGION_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PAYMENT_BLOCK_NAME'])) {
    $arParams['MESS_PAYMENT_BLOCK_NAME'] = Loc::getMessage('PAYMENT_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_BLOCK_NAME'])) {
    $arParams['MESS_DELIVERY_BLOCK_NAME'] = Loc::getMessage('DELIVERY_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_BUYER_BLOCK_NAME'])) {
    $arParams['MESS_BUYER_BLOCK_NAME'] = Loc::getMessage('BUYER_BLOCK_NAME_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_BACK'])) {
    $arParams['MESS_BACK'] = Loc::getMessage('BACK_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_FURTHER'])) {
    $arParams['MESS_FURTHER'] = Loc::getMessage('FURTHER_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_EDIT'])) {
    $arParams['MESS_EDIT'] = Loc::getMessage('EDIT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ORDER'])) {
    $arParams['MESS_ORDER'] = Loc::getMessage('ORDER_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PRICE'])) {
    $arParams['MESS_PRICE'] = Loc::getMessage('PRICE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PERIOD'])) {
    $arParams['MESS_PERIOD'] = Loc::getMessage('PERIOD_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NAV_BACK'])) {
    $arParams['MESS_NAV_BACK'] = Loc::getMessage('NAV_BACK_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NAV_FORWARD'])) {
    $arParams['MESS_NAV_FORWARD'] = Loc::getMessage('NAV_FORWARD_DEFAULT');
}

$useDefaultMessages = !isset($arParams['USE_CUSTOM_ADDITIONAL_MESSAGES']) || $arParams['USE_CUSTOM_ADDITIONAL_MESSAGES'] != 'Y';

if ($useDefaultMessages || !isset($arParams['MESS_PRICE_FREE'])) {
    $arParams['MESS_PRICE_FREE'] = Loc::getMessage('PRICE_FREE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ECONOMY'])) {
    $arParams['MESS_ECONOMY'] = Loc::getMessage('ECONOMY_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGISTRATION_REFERENCE'])) {
    $arParams['MESS_REGISTRATION_REFERENCE'] = Loc::getMessage('REGISTRATION_REFERENCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_1'])) {
    $arParams['MESS_AUTH_REFERENCE_1'] = Loc::getMessage('AUTH_REFERENCE_1_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_2'])) {
    $arParams['MESS_AUTH_REFERENCE_2'] = Loc::getMessage('AUTH_REFERENCE_2_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_AUTH_REFERENCE_3'])) {
    $arParams['MESS_AUTH_REFERENCE_3'] = Loc::getMessage('AUTH_REFERENCE_3_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ADDITIONAL_PROPS'])) {
    $arParams['MESS_ADDITIONAL_PROPS'] = Loc::getMessage('ADDITIONAL_PROPS_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_USE_COUPON'])) {
    $arParams['MESS_USE_COUPON'] = Loc::getMessage('USE_COUPON_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_COUPON'])) {
    $arParams['MESS_COUPON'] = Loc::getMessage('COUPON_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PERSON_TYPE'])) {
    $arParams['MESS_PERSON_TYPE'] = Loc::getMessage('PERSON_TYPE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SELECT_PROFILE'])) {
    $arParams['MESS_SELECT_PROFILE'] = Loc::getMessage('SELECT_PROFILE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_REGION_REFERENCE'])) {
    $arParams['MESS_REGION_REFERENCE'] = Loc::getMessage('REGION_REFERENCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PICKUP_LIST'])) {
    $arParams['MESS_PICKUP_LIST'] = Loc::getMessage('PICKUP_LIST_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_NEAREST_PICKUP_LIST'])) {
    $arParams['MESS_NEAREST_PICKUP_LIST'] = Loc::getMessage('NEAREST_PICKUP_LIST_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SELECT_PICKUP'])) {
    $arParams['MESS_SELECT_PICKUP'] = Loc::getMessage('SELECT_PICKUP_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_INNER_PS_BALANCE'])) {
    $arParams['MESS_INNER_PS_BALANCE'] = Loc::getMessage('INNER_PS_BALANCE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_ORDER_DESC'])) {
    $arParams['MESS_ORDER_DESC'] = Loc::getMessage('ORDER_DESC_DEFAULT');
}

$useDefaultMessages = !isset($arParams['USE_CUSTOM_ERROR_MESSAGES']) || $arParams['USE_CUSTOM_ERROR_MESSAGES'] != 'Y';

if ($useDefaultMessages || !isset($arParams['MESS_PRELOAD_ORDER_TITLE'])) {
    $arParams['MESS_PRELOAD_ORDER_TITLE'] = Loc::getMessage('PRELOAD_ORDER_TITLE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_SUCCESS_PRELOAD_TEXT'])) {
    $arParams['MESS_SUCCESS_PRELOAD_TEXT'] = Loc::getMessage('SUCCESS_PRELOAD_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_FAIL_PRELOAD_TEXT'])) {
    $arParams['MESS_FAIL_PRELOAD_TEXT'] = Loc::getMessage('FAIL_PRELOAD_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_CALC_ERROR_TITLE'])) {
    $arParams['MESS_DELIVERY_CALC_ERROR_TITLE'] = Loc::getMessage('DELIVERY_CALC_ERROR_TITLE_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_DELIVERY_CALC_ERROR_TEXT'])) {
    $arParams['MESS_DELIVERY_CALC_ERROR_TEXT'] = Loc::getMessage('DELIVERY_CALC_ERROR_TEXT_DEFAULT');
}

if ($useDefaultMessages || !isset($arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'])) {
    $arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR'] = Loc::getMessage('PAY_SYSTEM_PAYABLE_ERROR_DEFAULT');
}

$scheme = $request->isHttps() ? 'https' : 'http';
switch (LANGUAGE_ID) {
    case 'ru':
        $locale = 'ru-RU';
        break;
    case 'ua':
        $locale = 'ru-UA';
        break;
    case 'tk':
        $locale = 'tr-TR';
        break;
    default:
        $locale = 'en-US';
        break;
}

$this->addExternalJs($templateFolder . '/order_ajax.js');
\Bitrix\Sale\PropertyValueCollection::initJs();
$this->addExternalJs($templateFolder . '/script.js');
?>
    <script src="https://forma.tinkoff.ru/static/onlineScript.js"></script>
    <NOSCRIPT>
        <div style="color:red"><?= Loc::getMessage('SOA_NO_JS') ?></div>
    </NOSCRIPT>
<?

if (strlen($request->get('ORDER_ID')) > 0) {
    include($server->getDocumentRoot() . $templateFolder . '/confirm.php');
} elseif ($arParams['DISABLE_BASKET_REDIRECT'] === 'Y' && $arResult['SHOW_EMPTY_BASKET']) {
    include($server->getDocumentRoot() . $templateFolder . '/empty.php');
} else {
    $hideDelivery = empty($arResult['DELIVERY']);
    ?>
    <form action="<?= $APPLICATION->GetCurPage(); ?>" method="POST" name="ORDER_FORM" id="bx-soa-order-form"
          enctype="multipart/form-data">
        <?
        echo bitrix_sessid_post();

        if (strlen($arResult['PREPAY_ADIT_FIELDS']) > 0) {
            echo $arResult['PREPAY_ADIT_FIELDS'];
        }
        ?>
        <input type="hidden" name="<?= $arParams['ACTION_VARIABLE'] ?>" value="saveOrderAjax">
        <input type="hidden" name="location_type" value="code">
        <input type="hidden" name="BUYER_STORE" id="BUYER_STORE" value="<?= $arResult['BUYER_STORE'] ?>">
        <div id="bx-soa-order" class="row bx-<?= $arParams['TEMPLATE_THEME'] ?> center-content" style="opacity: 0">
            <!--	MAIN BLOCK	-->
            <div class="col-sm-9 bx-soa">
                <div id="bx-soa-main-notifications">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div data-type="informer" style="display:none"></div>
                </div>
                <!--	AUTH BLOCK	-->
                <div id="bx-soa-auth" class="bx-soa-section bx-soa-auth" style="display:none">
                    <div class="bx-soa-section-title-container">
                        <span class="bx-soa-section-title col-sm-9">
                            <?= $arParams['MESS_AUTH_BLOCK_NAME'] ?>
                        </span>
                    </div>
                    <div class="bx-soa-section-content container-fluid"></div>
                </div>

                <!--	DUPLICATE MOBILE ORDER SAVE BLOCK	-->
                <div id="bx-soa-total-mobile" class="bx-soa-total-mobile"></div>

                <!--	BASKET ITEMS BLOCK	-->

                <?$APPLICATION->IncludeComponent(
                    "bitrix:sale.basket.basket",
                    "basket.DJ.ajax",
                    array(
                        "PATH_TO_ORDER" => "/personal/order/make/",
                        "QUANTITY_FLOAT" => "N",
                        "PRICE_VAT_SHOW_VALUE" => "Y",
                        "TEMPLATE_THEME" => "site",
                        "SET_TITLE" => "Y",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "COLUMNS_LIST_EXT" => array(
                            0 => "PREVIEW_PICTURE",
                            1 => "DISCOUNT",
                            2 => "DELETE",
                            3 => "DELAY",
                            4 => "TYPE",
                            5 => "SUM",
                            6 => "PROPERTY_ARTNUMBER",
                            7 => "PROPERTY_PRODUCT_TYPE",
                        ),
                        "COMPONENT_TEMPLATE" => "basket.DJ.ajax",
                        "DEFERRED_REFRESH" => "N",
                        "USE_DYNAMIC_SCROLL" => "Y",
                        "SHOW_FILTER" => "Y",
                        "SHOW_RESTORE" => "Y",
                        "COLUMNS_LIST_MOBILE" => array(
                            0 => "PREVIEW_PICTURE",
                            1 => "DISCOUNT",
                            2 => "DELETE",
                            3 => "DELAY",
                            4 => "TYPE",
                            5 => "SUM",
                            6 => "PROPERTY_ARTNUMBER",
                            7 => "PROPERTY_PRODUCT_TYPE",
                        ),
                        "TOTAL_BLOCK_DISPLAY" => array(
                            0 => "top",
                        ),
                        "DISPLAY_MODE" => "extended",
                        "PRICE_DISPLAY_MODE" => "Y",
                        "SHOW_DISCOUNT_PERCENT" => "Y",
                        "DISCOUNT_PERCENT_POSITION" => "bottom-right",
                        "PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
                        "USE_PRICE_ANIMATION" => "Y",
                        "LABEL_PROP" => array(
                        ),
                        "HIDE_COUPON" => "N",
                        "USE_PREPAYMENT" => "N",
                        "CORRECT_RATIO" => "Y",
                        "AUTO_CALCULATION" => "Y",
                        "ACTION_VARIABLE" => "basketAction",
                        "COMPATIBLE_MODE" => "Y",
                        "EMPTY_BASKET_HINT_PATH" => "/",
                        "ADDITIONAL_PICT_PROP_2" => "-",
                        "ADDITIONAL_PICT_PROP_3" => "-",
                        "BASKET_IMAGES_SCALING" => "adaptive",
                        "USE_GIFTS" => "Y",
                        "GIFTS_PLACE" => "BOTTOM",
                        "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
                        "GIFTS_HIDE_BLOCK_TITLE" => "N",
                        "GIFTS_TEXT_LABEL_GIFT" => "Подарок",
                        "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
                        "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
                        "GIFTS_SHOW_OLD_PRICE" => "N",
                        "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
                        "GIFTS_MESS_BTN_BUY" => "Выбрать",
                        "GIFTS_MESS_BTN_DETAIL" => "Подробнее",
                        "GIFTS_PAGE_ELEMENT_COUNT" => "4",
                        "GIFTS_CONVERT_CURRENCY" => "N",
                        "GIFTS_HIDE_NOT_AVAILABLE" => "N",
                        "USE_ENHANCED_ECOMMERCE" => "N"
                    ), $component
                );?>
                <!--	BUYER PROPS BLOCK	-->
                <div id="bx-soa-properties" data-visited="false" class="bx-soa-section bx-active">
                    <div class="bx-soa-section-title-container">
                        <span class="bx-soa-section-title col-sm-9">
                            <?= $arParams['MESS_BUYER_BLOCK_NAME'] ?>
                        </span>
                    </div>
                    <div class="bx-soa-section-content container-fluid"></div>
                </div>

                <!--	PAY SYSTEMS BLOCK	-->
                <div id="bx-soa-paysystem" data-visited="false" class="bx-soa-section bx-active">
                    <div class="bx-soa-section-title-container">
                        <span class="bx-soa-section-title col-sm-9">
                            <?= $arParams['MESS_PAYMENT_BLOCK_NAME'] ?>
                        </span>
                    </div>
                    <div class="bx-soa-section-content container-fluid"></div>
                </div>

                <!--	REGION BLOCK	-->
                <div id="bx-soa-region" data-visited="false" class="bx-soa-section bx-active">
                    <div class="bx-soa-section-title-container">
                        <span class="bx-soa-section-title col-sm-9">
                            <?= $arParams['MESS_REGION_BLOCK_NAME'] ?>
                        </span>
                    </div>
                    <div class="bx-soa-section-content container-fluid"></div>
                </div>
                <!--	DELIVERY BLOCK	-->
                <div id="bx-soa-delivery" data-visited="false"
                     class="bx-soa-section bx-active" <?= ($hideDelivery ? 'style="display:none"' : '') ?>>
                    <div class="bx-soa-section-title-container">
                    </div>
                    <div class="bx-soa-section-content container-fluid"></div>
                </div>
                <!--	PICKUP BLOCK	-->
                <div id="bx-soa-pickup" data-visited="false" class="bx-soa-section" style="display:none">

                    <div class="bx-soa-section-title-container">
                        <span class="bx-soa-section-title col-sm-9">

                        </span>
                    </div>
                    <div class="bx-soa-section-content container-fluid"></div>
                </div>
                <!--	ORDER SAVE BLOCK	-->
                <div id="bx-soa-orderSave">
                    <div id="commentBlock"></div>
                    <div class="checkbox">
                        <?
                        if ($arParams['USER_CONSENT'] === 'Y') {
                            $APPLICATION->IncludeComponent(
                                'bitrix:main.userconsent.request',
                                '',
                                array(
                                    'ID' => $arParams['USER_CONSENT_ID'],
                                    'IS_CHECKED' => $arParams['USER_CONSENT_IS_CHECKED'],
                                    'IS_LOADED' => $arParams['USER_CONSENT_IS_LOADED'],
                                    'AUTO_SAVE' => 'N',
                                    'SUBMIT_EVENT_NAME' => 'bx-soa-order-save',
                                    'REPLACE' => array(
                                        'button_caption' => isset($arParams['~MESS_ORDER']) ? $arParams['~MESS_ORDER'] : $arParams['MESS_ORDER'],
                                        'fields' => $arResult['USER_CONSENT_PROPERTY_DATA']
                                    )
                                )
                            );
                        }
                        ?>
                    </div>
                    <button id="order_save_button" style="margin: 10px 0; display: block"
                            class="pull-right btn btn-default btn-lg hidden-xs"
                            data-save-button="true"><?= $arParams['MESS_ORDER'] ?></button>
                </div>
                <div style="display: none;">
                    <div id='bx-soa-basket-hidden' class="bx-soa-section"></div>
                    <div id='bx-soa-region-hidden' class="bx-soa-section"></div>
                    <div id='bx-soa-paysystem-hidden' class="bx-soa-section"></div>
                    <div id='bx-soa-delivery-hidden' class="bx-soa-section"></div>
                    <div id='bx-soa-pickup-hidden' class="bx-soa-section"></div>
                    <div id="bx-soa-properties-hidden" class="bx-soa-section"></div>
                    <div id="bx-soa-auth-hidden" class="bx-soa-section">
                        <div class="bx-soa-section-content container-fluid reg"></div>
                    </div>
                </div>
            </div>

            <!--	SIDEBAR BLOCK	-->
            <div id="bx-soa-total" class="col-sm-3 bx-soa-sidebar" style="display: none">
                <div class="bx-soa-cart-total-ghost"></div>
                <div class="bx-soa-cart-total"></div>
            </div>
        </div>
    </form>
    <div id="bx-soa-saved-files" style="display:none"></div>
    <div id="bx-soa-soc-auth-services" style="display:none">
        <?
        $arServices = false;
        $arResult['ALLOW_SOCSERV_AUTHORIZATION'] = Main\Config\Option::get('main', 'allow_socserv_authorization', 'Y') != 'N' ? 'Y' : 'N';
        $arResult['FOR_INTRANET'] = false;

        if (Main\ModuleManager::isModuleInstalled('intranet') || Main\ModuleManager::isModuleInstalled('rest'))
            $arResult['FOR_INTRANET'] = true;

        if (Main\Loader::includeModule('socialservices') && $arResult['ALLOW_SOCSERV_AUTHORIZATION'] === 'Y') {
            $oAuthManager = new CSocServAuthManager();
            $arServices = $oAuthManager->GetActiveAuthServices(array(
                'BACKURL' => $this->arParams['~CURRENT_PAGE'],
                'FOR_INTRANET' => $arResult['FOR_INTRANET'],
            ));

            if (!empty($arServices)) {
                $APPLICATION->IncludeComponent(
                    'bitrix:socserv.auth.form',
                    'flat',
                    array(
                        'AUTH_SERVICES' => $arServices,
                        'AUTH_URL' => $arParams['~CURRENT_PAGE'],
                        'POST' => $arResult['POST'],
                    ),
                    $component,
                    array('HIDE_ICONS' => 'Y')
                );
            }
        }
        ?>
    </div>

    <div style="display: none">
        <?
        // we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it
        $APPLICATION->IncludeComponent(
            "bitrix:sale.location.selector.steps",
            ".default",
            array(
                "COMPONENT_TEMPLATE" => ".default",
                "ID" => "",
                "CODE" => "",
                "INPUT_NAME" => "LOCATION",
                "PROVIDE_LINK_BY" => "id",
                "PRESELECT_TREE_TRUNK" => "N",
                "PRECACHE_LAST_LEVEL" => "N",
                "FILTER_BY_SITE" => "N",
                "SHOW_DEFAULT_LOCATIONS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "JS_CONTROL_GLOBAL_ID" => "",
                "JS_CALLBACK" => "",
                "SUPPRESS_ERRORS" => "N",
                "DISABLE_KEYBOARD_INPUT" => "Y",
                "INITIALIZE_BY_GLOBAL_EVENT" => "",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO"
            ),
            false
        );
        $APPLICATION->IncludeComponent(
            'bitrix:sale.location.selector.search',
            'main',
            array(),
            false
        );
        ?>
    </div>
    <?
    $signer = new Main\Security\Sign\Signer;
    $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.order.ajax');
    $messages = Loc::loadLanguageFile(__FILE__);
    ?>
    <script>
        BX.message(<?=CUtil::PhpToJSObject($messages)?>);
        var oac = BX.Sale.OrderAjaxComponent.init({
            result: <?=CUtil::PhpToJSObject($arResult['JS_DATA'])?>,
            locations: <?=CUtil::PhpToJSObject($arResult['LOCATIONS'])?>,
            params: <?=CUtil::PhpToJSObject($arParams)?>,
            signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
            siteID: '<?=CUtil::JSEscape($component->getSiteId())?>',
            ajaxUrl: '<?=CUtil::JSEscape($component->getPath() . '/ajax.php')?>',
            templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
            propertyValidation: true,
            showWarnings: true,
            pickUpMap: {
                defaultMapPosition: {
                    lat: 55.76,
                    lon: 37.64,
                    zoom: 7
                },
                secureGeoLocation: false,
                geoLocationMaxTime: 5000,
                minToShowNearestBlock: 3,
                nearestPickUpsToShow: 3
            },
            propertyMap: {
                defaultMapPosition: {
                    lat: 55.76,
                    lon: 37.64,
                    zoom: 7
                }
            },
            orderBlockId: 'bx-soa-order',
            authBlockId: 'bx-soa-auth',
            basketBlockId: 'bx-soa-basket',
            regionBlockId: 'bx-soa-region',
            paySystemBlockId: 'bx-soa-paysystem',
            deliveryBlockId: 'bx-soa-delivery',
            pickUpBlockId: 'bx-soa-pickup',
            propsBlockId: 'bx-soa-properties',
            totalBlockId: 'bx-soa-total'
        });
    </script>
    <script>
        <?
        // spike: for children of cities we place this prompt
        $city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
        ?>
        BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
            'source' => $component->getPath() . '/get.php',
            'cityTypeId' => intval($city['ID']),
            'messages' => array(
                'otherLocation' => '--- ' . Loc::getMessage('SOA_OTHER_LOCATION'),
                'moreInfoLocation' => '--- ' . Loc::getMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
                'notFoundPrompt' => '<div class="-bx-popup-special-prompt">' . Loc::getMessage('SOA_LOCATION_NOT_FOUND') . '.<br />' . Loc::getMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
                        '#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
                        '#ANCHOR_END#' => '</a>'
                    )) . '</div>'
            )
        ))?>);
    </script>
    <?
    if ($arParams['SHOW_PICKUP_MAP'] === 'Y' || $arParams['SHOW_MAP_IN_PROPS'] === 'Y') {
        if ($arParams['PICKUP_MAP_TYPE'] === 'yandex') {
            $this->addExternalJs($templateFolder . '/scripts/yandex_maps.js');
            ?>
            <script src="<?= $scheme ?>://api-maps.yandex.ru/2.1.50/?load=package.full&lang=<?= $locale ?>"></script>
            <script>
                (function bx_ymaps_waiter() {
                    if (typeof ymaps !== 'undefined' && BX.Sale && BX.Sale.OrderAjaxComponent)
                        ymaps.ready(BX.proxy(BX.Sale.OrderAjaxComponent.initMaps, BX.Sale.OrderAjaxComponent));
                    else
                        setTimeout(bx_ymaps_waiter, 100);
                })();
            </script>
            <?
        }

        if ($arParams['PICKUP_MAP_TYPE'] === 'google') {
            $this->addExternalJs($templateFolder . '/scripts/google_maps.js');
            $apiKey = htmlspecialcharsbx(Main\Config\Option::get('fileman', 'google_map_api_key', ''));
            ?>
            <script async defer
                    src="<?= $scheme ?>://maps.googleapis.com/maps/api/js?key=<?= $apiKey ?>&callback=bx_gmaps_waiter">
            </script>
            <script>
                function bx_gmaps_waiter() {
                    if (BX.Sale && BX.Sale.OrderAjaxComponent)
                        BX.Sale.OrderAjaxComponent.initMaps();
                    else
                        setTimeout(bx_gmaps_waiter, 100);
                }
            </script>
            <?
        }
    }

    if ($arParams['USE_YM_GOALS'] === 'Y') {
        ?>
        <script>
            (function bx_counter_waiter(i) {
                i = i || 0;
                if (i > 50)
                    return;

                if (typeof window['yaCounter<?=$arParams['YM_GOALS_COUNTER']?>'] !== 'undefined')
                    BX.Sale.OrderAjaxComponent.reachGoal('initialization');
                else
                    setTimeout(function () {
                        bx_counter_waiter(++i)
                    }, 100);
            })();
        </script>
        <?
    }
}
?>