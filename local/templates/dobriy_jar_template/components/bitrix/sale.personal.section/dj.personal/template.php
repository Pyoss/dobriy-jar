<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;


if ($arParams["MAIN_CHAIN_NAME"] <> '') {
    $APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}

$this->addExternalCss("/bitrix/css/main/font-awesome.css");
$theme = Bitrix\Main\Config\Option::get("main", "wizard_eshop_bootstrap_theme_id", "blue", SITE_ID);

$availablePages = array();

if ($arParams['SHOW_ORDER_PAGE'] === 'Y') {
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_ORDERS'],
        "name" => Loc::getMessage("SPS_ORDER_PAGE_NAME"),
        "icon" => '<i class="fa fa-calculator"></i>'
    );
}

if ($arParams['SHOW_ACCOUNT_PAGE'] === 'Y') {
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_ACCOUNT'],
        "name" => Loc::getMessage("SPS_ACCOUNT_PAGE_NAME"),
        "icon" => '<i class="fa fa-credit-card"></i>'
    );
}

if ($arParams['SHOW_PRIVATE_PAGE'] === 'Y') {
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_PRIVATE'],
        "name" => Loc::getMessage("SPS_PERSONAL_PAGE_NAME"),
        "icon" => '<i class="fa fa-user-secret"></i>'
    );
}

if ($arParams['SHOW_ORDER_PAGE'] === 'Y') {

    $delimeter = ($arParams['SEF_MODE'] === 'Y') ? "?" : "&";
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_ORDERS'] . $delimeter . "filter_history=Y",
        "name" => Loc::getMessage("SPS_ORDER_PAGE_HISTORY"),
        "icon" => '<i class="fa fa-list-alt"></i>'
    );
}

if ($arParams['SHOW_PROFILE_PAGE'] === 'Y') {
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_PROFILE'],
        "name" => Loc::getMessage("SPS_PROFILE_PAGE_NAME"),
        "icon" => '<i class="fa fa-list-ol"></i>'
    );
}

if ($arParams['SHOW_BASKET_PAGE'] === 'Y') {
    $availablePages[] = array(
        "path" => $arParams['PATH_TO_BASKET'],
        "name" => Loc::getMessage("SPS_BASKET_PAGE_NAME"),
        "icon" => '<i class="fa fa-shopping-cart"></i>'
    );
}

if ($arParams['SHOW_SUBSCRIBE_PAGE'] === 'Y') {
    $availablePages[] = array(
        "path" => $arResult['PATH_TO_SUBSCRIBE'],
        "name" => Loc::getMessage("SPS_SUBSCRIBE_PAGE_NAME"),
        "icon" => '<i class="fa fa-envelope"></i>'
    );
}

if ($arParams['SHOW_CONTACT_PAGE'] === 'Y') {
    $availablePages[] = array(
        "path" => $arParams['PATH_TO_CONTACT'],
        "name" => Loc::getMessage("SPS_CONTACT_PAGE_NAME"),
        "icon" => '<i class="fa fa-info-circle"></i>'
    );
}

$customPagesList = CUtil::JsObjectToPhp($arParams['~CUSTOM_PAGES']);
if ($customPagesList) {
    foreach ($customPagesList as $page) {
        $availablePages[] = array(
            "path" => $page[0],
            "name" => $page[1],
            "icon" => (mb_strlen($page[2])) ? '<i class="fa ' . htmlspecialcharsbx($page[2]) . '"></i>' : ""
        );
    }
}
?>
<div class="personal-account">
    <span class="personal-account__user">
        <?= $arResult['USER']['NAME'] ?> <?= $arResult['USER']['LAST_NAME'] ?>
    </span>
    <div class="personal-account__bonus">
        <span class="personal-account__points">
            <span>Бонусные баллы: </span>
            <span class="points"><?=round($arResult['USER']['BONUS_ACCOUNT']['CURRENT_BUDGET'])?> ₽</span>
        </span>
        <br>
        <button class="dj_link" onclick="BX.toggleClass(BX('bonus-tip'), 'active')">Как получить?</button>
        <span id="bonus-tip" class="personal-account__aquire-tip">
            С каждой покупки в интернет-магазин Добрый Жар вам начисляется 3% от суммы заказа в виде баллов.
            <br>
            Вы можете оплатить баллами до 10% от суммы заказа (без учета доставки)</span>

    </div>
</div>

<?php
if (empty($availablePages)) {
    ShowError(Loc::getMessage("SPS_ERROR_NOT_CHOSEN_ELEMENT"));
} else {
    ?>
    <div class="personal-links">
        <? foreach ($availablePages as $blockElement) {
            ?>
            <a class="personal-links__link" href="<?= htmlspecialcharsbx($blockElement['path']) ?>">
            <span class="personal-links__icon">
                <?= $blockElement['icon'] ?>
            </span>
                <h2 class="personal-links__name">
                    <?= htmlspecialcharsbx($blockElement['name']) ?>
                </h2>
            </a>
            <?
        }
        ?>
    </div>
    <?
}
?>
<a class="dj_link" href="?logout=true" style="font-size: 20px;">
    <i class="fa fa-times" aria-hidden="true"></i> Выйти
</a>
