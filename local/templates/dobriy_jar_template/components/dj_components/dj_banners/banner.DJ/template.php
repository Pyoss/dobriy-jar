<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$width = $arParams['WIDTH'];
$height = $arParams['HEIGHT'];
$slide_view = ($arParams['SLIDERS_VIEW'] < count($arResult['BANNER_DATA'])) ? $arParams['SLIDERS_VIEW'] : count($arResult['BANNER_DATA']);
$scroll_speed = $arParams['SCROLL_SPEED'];
$endless = $arParams['ENDLESS'] == 'Y';
$VIEW_OFFSET = (int)($width/$slide_view);

if ($arParams['BANNER_TYPE']):?>

<div id="<?=$arParams['BANNER_ID']?>" class="slider <?=$arParams['BANNER_TYPE']?> noselect"
     style="max-width:<?php echo $width?>px;
         font-size: 20px"
     data-min-width="<?=$arParams['MIN-SLIDE-WIDTH']?>"
     data-slides="<?php echo $slide_view?>"
     data-endless="<?php echo (int)$endless?>"
     data-interval="<?php echo $arParams['SCROLL_SPEED']?>">
    <?
    $ADDITIONAL_TEMPLATE = '/' . $arParams['BANNER_TYPE'] . '/template.php';
    include dirname(__FILE__) . $ADDITIONAL_TEMPLATE;
    ?>
    <? if ($arParams["CONTROLS"] !== "HIDDEN"):?>
    <a class="control prev <?=$arParams["CONTROLS"]?>"></a>
    <a class="control next <?=$arParams["CONTROLS"]?>"></a>
    <? endif;?>
</div>
<script>
    new Slider('<?=$arParams['BANNER_ID']?>');
</script>
<?php endif?>