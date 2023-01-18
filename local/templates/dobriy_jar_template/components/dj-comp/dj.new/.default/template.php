<?php


/** @var array $arParams */
/** @var array $arResult */

?>
<section class="new section">
    <div class="new-links__title container__title">
        <span class="container__title-current">Новинки</span>
        <span class="container__title-slash">/</span>
        <a class="container__title-link" href="/catalog/">Показать всё</a>
    </div>
    <div class="new__list">
        <? foreach ($arResult['ITEMS'] as $ITEM): ?>
            <div class="new__item">
                <div class="new__info">
                    <div class="new__info-sale">NEW</div>
                    <span class="new__info-type">
            <?= $ITEM['TYPE'] ?></span>
                    <br>
                    <span class="new__info-name">
            <?= $ITEM['VIEW_NAME'] ?></span>
                    <br>
                    <span class="new__info-article"></span>
                </div>
                <img src="<?= $ITEM['img'] ?>" alt="" class="new__img">
                <span class="new__price">
                <?= (int)$ITEM['PRICE'] ?>
            </span>
                <button class="new__button">
                    Подробнее
                </button>
            </div>
        <? endforeach; ?>
    </div>
</section>
