<?php


/** @var array $arParams */
/** @var array $arResult */

?>
<div class="filter-slider__wrapper slick-slider__wrapper">
    <div class="filter-slider slick-template">
        <?php
        foreach ($arResult['BANNERS'] as $BANNER):
            ?>
        <a href="<?=$BANNER['CODE']?>">
            <img class="main-slider__img" src="<?= $BANNER['img']['auto'] ?>"></a>
        <? endforeach; ?>
    </div>
</div>
