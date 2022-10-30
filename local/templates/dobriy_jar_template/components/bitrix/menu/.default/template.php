<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
    <ul class="static-links">

<?
foreach($arResult as $arItem):
?>
    <li class="static-links--element"><a href="<?=$arItem["LINK"]?>" <?=$arItem["TEXT"] == 'Блог' ? 'target="_blank"' : ''?>><?=$arItem["TEXT"]?></a></li>
<?endforeach?>

</ul>
<?endif?>