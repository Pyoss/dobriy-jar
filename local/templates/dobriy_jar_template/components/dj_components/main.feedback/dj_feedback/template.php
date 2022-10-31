<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
use \Bitrix\Conversion\Internals\MobileDetect;

$detect = new MobileDetect;
?>
<section class="mfeedback-wrapper" id="mfeedback" <?
if(!$detect->isMobile()):
    ?>style="background-image: url(<?=$arParams['BACKGROUND_FILE']?>)"<?php
endif;?>>
    <div class="mfeedback-text top">Все ещё </br>раздумываете?</div>
    <div class="mfeedback-text bottom">Закажите обратный звонок, и наши специалисты проконсультируют вас и подберут лучший вариант</div>
    <div class="mfeedback" id="mfeedback-container">
    <div class="mfeedback-title">СВЯЗАТЬСЯ С НАМИ</div>
    <div class="mfeedback-phone">8(800) 600-45-96</div>
    <?if(!empty($arResult["ERROR_MESSAGE"]))
    {
        foreach($arResult["ERROR_MESSAGE"] as $v)
            ShowError($v);
    }
    if($arResult["OK_MESSAGE"] <> '')
    {
        ?><div class="mf-ok-text"><?=$arResult["OK_MESSAGE"]?></div><?
    }
    ?>
    <form action="<?=POST_FORM_ACTION_URI?>" method="POST" id="callback-form" onsubmit="
            let feedbackForm = new FeedbackForm(<?=CUtil::PhpToJSObject($arParams)?>);
            feedbackForm.send_mail(event);
        ">
    <?=bitrix_sessid_post()?>
        <div class="mf-name">
            <div class="mf-text">
                <?=GetMessage("MFT_NAME")?><?if(empty($arParams["REQUIRED_FIELDS"]) ||
                    in_array("NAME", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
            </div>
            <input type="text" name="name"
                   <?if(empty($arParams["REQUIRED_FIELDS"]) ||
                   in_array("NAME", $arParams["REQUIRED_FIELDS"])):?>required
                <?endif?>>
        </div>
        <?/*
        <div class="mf-email">
            <div class="mf-text">
                <?=GetMessage("MFT_EMAIL")?>
                <?if(empty($arParams["REQUIRED_FIELDS"]) ||
                    in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?>
                    <span class="mf-req">*</span><?endif?>
            </div>
            <input type="text" name="EMAIL"
                   <?if(empty($arParams["REQUIRED_FIELDS"]) ||
                   in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?>required<?endif?>>
        </div>
        <div class="mf-city">
            <div class="mf-text">
                <?=GetMessage("MFT_CITY")?><?if(empty($arParams["REQUIRED_FIELDS"]) ||
                    in_array("CITY", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
            </div>
            <input type="text" name="user_city"
                   <?if(empty($arParams["REQUIRED_FIELDS"]) ||
                   in_array("CITY", $arParams["REQUIRED_FIELDS"])):?>required<?endif?>>
        </div>
        */?>
        <div class="mf-number">
            <div class="mf-text">
                <?=GetMessage("MFT_PHONE")?><?if(empty($arParams["REQUIRED_FIELDS"]) ||
                    in_array("PHONE", $arParams["REQUIRED_FIELDS"])):?><span class="mf-req">*</span><?endif?>
            </div>
            <input type="tel" name="phone"
            <?if(empty($arParams["REQUIRED_FIELDS"]) ||
            in_array("PHONE", $arParams["REQUIRED_FIELDS"])):?>required<?endif?>
                   pattern="[+]?[0-9]{1,3}[ (.-]{0,2}[0-9]{3}[ ).-]{0,2}[0-9]{3}[ .-]{0,2}[0-9]{2}[ .-]{0,2}[0-9]{2}">
        </div>

        <?if($arParams["USE_CAPTCHA"] == "Y"):?>
        <div class="mf-captcha">
            <div class="mf-text"><?=GetMessage("MFT_CAPTCHA")?></div>
            <input type="hidden" name="captcha_sid" value="<?=$arResult["capCode"]?>">
            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["capCode"]?>" width="180" height="40" alt="CAPTCHA">
            <div class="mf-text"><?=GetMessage("MFT_CAPTCHA_CODE")?><span class="mf-req">*</span></div>
            <input type="text" name="captcha_word" size="30" maxlength="50" value="">
        </div>
        <?endif;?>
        <input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
        <input type="submit" name="submit" value="<?=GetMessage("MFT_SUBMIT")?>">
    </form>
    </div>
</section>