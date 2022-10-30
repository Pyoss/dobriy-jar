<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Page\Asset;
if ($arParams['BANNER_TYPE']){
    $ADDITIONAL_RESULT = '/' . $arParams['BANNER_TYPE'] . '/result_modifier.php';
    $template = & $this->GetFolder();

    include dirname(__FILE__) . $ADDITIONAL_RESULT;

    Asset::getInstance()->addCss(
        $this->GetFolder() . '/'
        . $arParams['BANNER_TYPE'] . '/style.css'
    );
}

