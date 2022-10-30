<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
if($arResult['SECTIONS']){
    ?>
    <div class="catalog--top-sections">
        <?
    foreach ($arResult['SECTIONS'] as $section):
        ?>
        <a href="<?=$section['SECTION_PAGE_URL']?>">
        <div class="top-section">
            <div class="top-section--image-wrapper">
                <img src="<?=$section['DETAIL_PICTURE']['src']?>">
            </div>

            <div class="top-section--name">
                <? echo $section['NAME']?>
            </div>
        </div>
        </a>
            <?
    endforeach;
    ?>
    </div><?php
}