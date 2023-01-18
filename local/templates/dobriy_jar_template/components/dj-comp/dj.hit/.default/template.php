<?php


/** @var array $arParams */
/** @var array $arResult */

?>
<section class="hit section">
    <div class="hit__title container__title">
        <span class="container__title-current">Хит продаж</span>
        <span class="container__title-slash">/</span>
        <a class="container__title-link" href="/catalog/">Показать всё</a>
    </div>
    <div class="hit__list">
        <? foreach ($arResult['ITEMS'] as $ITEM): ?>
            <div class="hit__item">
                <div class="hit__info">
                    <div class="hit__info-sale">15%</div>
                    <span class="hit__info-type">
            <?= $ITEM['TYPE'] ?></span>
                    <br>
                    <span class="hit__info-name">
            <?= $ITEM['VIEW_NAME'] ?></span>
                    <br>
                    <span class="hit__info-article"></span>
                </div>
                <img src="<?= $ITEM['img'] ?>" alt="" class="hit__img">
                <span class="hit__price">
                <?= (int)$ITEM['PRICE'] ?>
            </span>
                <button class="hit__button">
                    Подробнее
                </button>
            </div>
        <? endforeach; ?>
    </div>
</section>

