<?php


/** @var array $arParams */
/** @var array $arResult */

?>
<section class="popular-categories section">
    <div class="popular-categories__title container__title">
        <span class="container__title-current">Популярные категории</span>
        <span class="container__title-slash">/</span>
        <a class="container__title-link" href="/catalog/">Показать всё</a>
    </div>
    <div class="popular-categories__list" style='background-image: url("<?= $arResult['BACKGROUND'] ?>")'>
        <? foreach ($arResult['SECTIONS'] as $SECTION): ?>
            <? if ($SECTION === 'blank'): ?>
                <div class="popular-categories__item popular-categories__item-blank">

                </div>
            <? else: ?>
                    <div class="popular-categories__item">
                        <a href="/catalog/<?= $SECTION['CODE'] ?>">
                        </a>
                        <img src="<?= $SECTION['img'] ?>" alt="" class="popular-categories__img">
                        <div class="popular-categories__name">
                            <?= $SECTION['NAME'] ?>
                        </div>
                    </div>
            <? endif; ?>
        <? endforeach; ?>
    </div>
</section>
