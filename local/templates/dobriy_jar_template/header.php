<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= LANG_CHARSET; ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="date=no">
    <meta name="format-detection" content="address=no">
    <title><?= $APPLICATION->ShowTitle() ?></title>
    <?php

    $GLOBALS['phone'] = DJMain::getPhone();
    use Bitrix\Main\Page\Asset;

    // Загрузка ВСЕХ стилей для шаблона из папки template/css
    $cssDir = SITE_TEMPLATE_PATH . "/css/";
    $arDirCss = scandir($_SERVER['DOCUMENT_ROOT'] . $cssDir, 1);
    foreach ($arDirCss as $сssFile) {
        if (pathinfo($сssFile)['extension'] == "css") {
            $APPLICATION->SetAdditionalCSS($cssDir . $сssFile);
        };
    }

    function checkB2BUser()
    {
        global $USER;
        $arUserGroups = $USER->GetUserGroupArray();
        $b2bGroupId = 0;
        $result = \Bitrix\Main\GroupTable::getList(array(
            'select' => array('ID'),
            'filter' => array('STRING_ID' => 'b2b_clients')
        ));

        while ($arGroup = $result->fetch()) {
            $b2bGroupId = $arGroup['ID'];
        }
        if (in_array($b2bGroupId, $arUserGroups)) {
            $USER->Logout();
        }
        return true;

    }
    checkB2BUser();
    $assets = Asset::getInstance();
    $assets->addJs(SITE_TEMPLATE_PATH . "/js/jquery-3.6.0.min.js");
    $assets->addJs(SITE_TEMPLATE_PATH . "/js/jquery.mask.js");

    $assets->addJs(SITE_TEMPLATE_PATH . "/js/mobile.js");
    $assets->addJs(SITE_TEMPLATE_PATH . "/js/default.js");
    $assets->addJs(SITE_TEMPLATE_PATH . "/js/skusetter.js");
    include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/analytics.php";
    $APPLICATION->ShowHead();
    global $USER;; ?>
    <div id="panel"><?php
        if ($_GET['panel'] !== 'hidden' && $USER->GetID() != 12) {
            $APPLICATION->ShowPanel();
            Asset::getInstance()->addJs('/local/admin/custom.js');
        } ?></div>
    <script src="//code.jivo.ru/widget/mfRfI5Oga4" async></script>
</head>
<body>
<header>
    <div class="header-top">
        <div class="header-left">
            <?php $APPLICATION->IncludeComponent(
                'bitrix:menu',
                '.default',
                [
                    'COMPONENT_TEMPLATE' => '.default',
                    'ROOT_MENU_TYPE' => 'top',
                    'MENU_CACHE_TYPE' => 'N',
                    'MENU_CACHE_TIME' => '3600',
                    'MENU_CACHE_USE_GROUPS' => 'Y',
                    'MENU_CACHE_GET_VARS' => [
                    ],
                    'MAX_LEVEL' => '1',
                    'CHILD_MENU_TYPE' => 'left',
                    'USE_EXT' => 'N',
                    'DELAY' => 'N',
                    'ALLOW_MULTI_SELECT' => 'N'
                ],
                false
            ); ?>
        </div>
        <div class="header-right">
            <a class="call-back callback_button">ЗАКАЗАТЬ ЗВОНОК</a>
            <a id='call' class="phone-main" href="tel:<?= $GLOBALS['phone'] ?>">
                <div class="mobile">Телефон интернет-магазина</div>
                <i class="inline-icon phone-icon"></i>
                <?= $GLOBALS['phone'] ?></a>
        </div>
    </div>
    <div class="header-center dj-background">
        <div class="header-center--container">
            <div class="header-center--mobile-menu" id="mobile-catalog-open" data-popup-name="header-menu"></div>
            <a href="/" class="header-logo">
            </a>
            <div class="header-center--geo-container">
                <? $APPLICATION->IncludeComponent(
                    "dj_components:dj.geolocation",
                    ".default",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "IBLOCK_TYPE" => "regions",
                        "IBLOCK_ID" => "4",
                        "DOMAIN_PROP_ID" => "41"
                    ),
                    false
                ); ?>
            </div>
            <div class="header-search" id="header-search--container">
                <?php $APPLICATION->IncludeComponent(
                    "bitrix:search.title",
                    "search.DJ",
                    array(
                        "CATEGORY_0" => array(
                            0 => "iblock_catalog",
                        ),
                        "CATEGORY_0_TITLE" => "",
                        "CATEGORY_0_iblock_catalog" => array(
                            0 => "2",
                        ),
                        "CHECK_DATES" => "N",
                        "CONTAINER_ID" => "title-search",
                        "INPUT_ID" => "title-search-input",
                        "NUM_CATEGORIES" => "1",
                        "ORDER" => "rank",
                        "PAGE" => "#SITE_DIR#catalog/search.php",
                        "SHOW_INPUT" => "Y",
                        "SHOW_OTHERS" => "N",
                        "TOP_COUNT" => "5",
                        "USE_LANGUAGE_GUESS" => "Y",
                        "SHOW_PREVIEW" => "Y",
                        "COMPONENT_TEMPLATE" => "search.DJ",
                        "PRICE_CODE" => array(
                            0 => "RETAIL_PRICE",
                        ),
                        "CONVERT_CURRENCY" => "N"
                    ),
                    false
                ); ?>
            </div>
            <ul class="user-buttons desktop">
                <li class="messenger-buttons">
                    <a href="http://t.me/dobriyjar" target="_blank">
                        <img src="/images/png/tg_header.png">
                    </a>
                    <a href="https://wa.me/79645036043?text=Здравствуйте%2C+у+меня+есть+вопрос" target="_blank">
                        <img src="/images/png/whatsapp_header.png">
                    </a>
                </li>
                <?php $APPLICATION->IncludeComponent(
                    "bitrix:sale.basket.basket",
                    "basket.DJ.ajax",
                    array(
                        "HEADER_INFO" => 'Y'
                    ),
                    false
                ); ?>
                <li style='display: flex;flex-flow: column nowrap;justify-content: center;align-items: center;'
                    class="user-buttons--element personal desktop">
                    <a href="/personal/" class="inline-icon personal-icon"></a>
                    <a href="/personal/"
                       class="user-button--text desktop"><?= $USER->GetID() ? $USER->GetFirstName() : 'Войти'; ?></a>
                </li>
            </ul>
            <ul class="user-buttons mobile">
                <li class="user-buttons--element search">
                    <span class="inline-icon search-icon mobile-button" data-popup-name="search"></span>
                </li>
            </ul>
        </div>
    </div>
    <?php $APPLICATION->IncludeComponent(
        "bitrix:menu",
        "category",
        array(
            "COMPONENT_TEMPLATE" => "category",
            "ROOT_MENU_TYPE" => "catalog",
            "MENU_CACHE_TYPE" => "N",
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_GET_VARS" => array(),
            "MAX_LEVEL" => "1",
            "CHILD_MENU_TYPE" => "left",
            "USE_EXT" => "Y",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "N"
        ),
        false
    ); ?>

</header>
<main>