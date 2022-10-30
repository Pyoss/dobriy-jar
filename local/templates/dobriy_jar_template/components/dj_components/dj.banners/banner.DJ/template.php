<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$width = $arParams['WIDTH'];
$height = $arParams['HEIGHT'];
$slide_view = ($arParams['SLIDERS_VIEW'] < count($arResult['BANNER_DATA'])) ? $arParams['SLIDERS_VIEW'] : count($arResult['BANNER_DATA']);
$scroll_speed = $arParams['SCROLL_SPEED'];
$endless = $arParams['ENDLESS'] == 'Y';
$VIEW_OFFSET = (int)($width/$slide_view);

if ($arParams['BANNER_TYPE']):?>

<div id="<?=$arParams['BANNER_ID']?>" class="slider <?=$arParams['BANNER_TYPE']?> noselect">
    <?
    $ADDITIONAL_TEMPLATE = '/' . $arParams['BANNER_TYPE'] . '/template.php';
    include dirname(__FILE__) . $ADDITIONAL_TEMPLATE;
    ?>
</div>
    <?DJMain::consoleString($arResult)?>
<script>
    var main_slider = new SliderDev('<?=$arParams['BANNER_ID']?>', 1, <?=$arResult['RATIO']?>, <?=$arResult['MOB_RATIO']?>, true);
</script>
<?php endif?>