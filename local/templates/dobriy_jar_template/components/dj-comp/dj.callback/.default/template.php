<?php


/** @var array $arParams */
/** @var array $arResult */

?>
<section class="callback section" id="callback" style='background-image: url("<?= $arResult['images']['BACKGROUND'] ?>")'>
    <div class="callback__container">
        <div class="callback__wrapper">
            <h3 class="callback__title">Хотите заказать нашу продукцию или есть вопросы?</h3>
            <span class="callback__text">Напишите нам в удобный для вас мессенджер и мы проконсультируем вас в течение рабочего дня!</span>
            <div class="callback__messengers">
                <a href="">
                    <img src="<?= $arResult['images']['vb'] ?>" alt="" class="callback__messenger">
                </a>
                <a href="">
                    <img src="<?= $arResult['images']['tg'] ?>" alt="" class="callback__messenger">
                </a>
                <a href="">
                    <img src="<?= $arResult['images']['ws'] ?>" alt="" class="callback__messenger">
                </a>
            </div>
            <input type="text" class="callback__phone tel-mask" id='callback-input' placeholder="+ 7 (999) 999-99-99">
            <button class="callback__button" id='callback-button'>Заказать звонок</button>
        </div>
    </div>
</section>


