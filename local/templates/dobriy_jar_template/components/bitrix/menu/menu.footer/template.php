<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult)):?>
<ul class="footer-link-group">
    <li class="footer-link-group--title"><?=$arParams['FOOTER_MENU_NAME']?></li>
    <?foreach($arResult as $arItem):
        if ($arParams['EXT_ONLY'] == 'Y' && $arItem['ITEM_TYPE'] == 'P' || $arItem['DEPTH_LEVEL'] > 1) continue;?>

    <li class="footer-link-group--link"><a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a></li>
    <?endforeach?>
</ul>
<?endif?>