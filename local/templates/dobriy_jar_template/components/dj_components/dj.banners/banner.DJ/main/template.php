<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<div class="wrapper">
    <div class="slides" style="left:-0px">
        <?php
        $index = 0;
        foreach($arResult['BANNER_DATA'] as $banner):?>
            <div class="slide" data-href="<?=$banner['CODE']?>" data-index="<?=$banner['INDEX']?>">
                <img class="slide-background" data-mobile-src="<?=$banner['MOBIMG']?>" data-desktop-src="<?=$banner['IMG']?>">
            </div>
        <?php endforeach;?>
    </div>
</div>
<div class="bullets-wrapper">

    <?php foreach($arResult['BANNER_DATA'] as $banner):?>
        <span class="bullet" data-index="<?=$banner['INDEX']?>">
        </span>
    <?php endforeach;?>
</div>
