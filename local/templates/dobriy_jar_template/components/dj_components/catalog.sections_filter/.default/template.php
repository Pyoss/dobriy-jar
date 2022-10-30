<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();}

$foundSections = $arResult['FOUND_SECTIONS'];
?>
<div class="found-sections">
    <div class="found-section--title">Найдено в категориях </div>
    <?foreach ($foundSections as $foundSection):?>
        <div class="found-section<?=$_GET['SECTION_ID'] == $foundSection['SECTION_ID']?" active": ""?>" data-section-id="<?=$foundSection['SECTION_ID']?>">
            <span class="found-section--name"><?=$foundSection['NAME']?></span>
            <span class="found-section--quantity"><?=$foundSection['QUANTITY']?></span>
        </div>
    <?endforeach;?>
    <?
    /*$APPLICATION->IncludeComponent(
        "dj_components:dj.banners",
        "banner.DJ",
        array(
            "BANNER_ID" => "filter",
            "BANNER_TYPE" => "filter",
            "COMPONENT_TEMPLATE" => "banner.DJ",
            "CONTROLS" => "HIDDEN",
            "ENDLESS" => "Y",
            "HEIGHT" => "450",
            "MIN-SLIDE-WIDTH" => "100",
            "SCROLL_SPEED" => "0",
            "SLIDERS_VIEW" => "1",
            "WIDTH" => "300"
        ),
        false
    );
    */?>
</div>
<script type="text/javascript">
    var smartFilter = new JSSearchFilter();
</script>
