<?php


/** @var array $arParams */
/** @var array $arResult */

?>
<div class="main-slider__wrapper slick-slider__wrapper">
<div class="main-slider slick-template">
    <?php
        foreach($arResult['BANNERS'] as $BANNER):
    ?>
        <img class="main-slider__img" src="<?=$BANNER['img']['auto']?>">
    <? endforeach;?>
</div>
</div>
