<?php


/** @var array $arParams */
/** @var array $arResult */

?><section class="blog-links section"   >
    <div class="blog-links__title container__title">
        <span class="container__title-current">БЛОГ</span>
        <span class="container__title-slash">/</span>
        <a class="container__title-link">Показать всё</a>
    </div>
    <div class="blog-links__container">
        <? foreach ($arResult['LINKS'] as $LINK):?>
            <a href="<?=$LINK['CODE']?>" class="blog-links__link">
                <img src="<?=$LINK['IMG']?>" alt="" class="blog-links__img">
            </a>
        <? endforeach;?>
    </div>
</section>