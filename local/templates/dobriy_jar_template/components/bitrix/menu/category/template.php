<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <ul class="section-menu">
        <?
        foreach ($arResult['SECTIONS'] as $arSection):?>
            <li class="section-menu--section"><a href="<?= $arSection['LINK'] ?>"><span
                            class="section-menu--name image-hover"
                            data-src="<?= $arSection['PARAMS']['DETAIL_PICTURE'] ?>">
            <?= $arSection['TEXT'] ?>
        </span></a>
                <?
                if ($arSection['IS_PARENT']):?>
                    <div class="subsection-container">
                        <div class="subsection-container--content">
                            <? $section_counter = 0;
                            while ($section_counter < 8 && $section_counter < count($arSection['CHILDREN'])):?>
                                <ul class="subsection-menu">
                                    <?
                                    //
                                    for ($j = 0; $section_counter < count($arSection['CHILDREN']) && $j < $arParams['COLUMN_NUMBER']; $j++, $section_counter++):
                                        $arSubSection = $arSection['CHILDREN'][$section_counter] ?>
                                        <li class="subsection-menu--section">
                                            <a href="<?= $arSubSection['LINK'] ?>"><span
                                                        class="subsection-menu--name image-hover"
                                                        data-src="<?= $arSubSection['PARAMS']['DETAIL_PICTURE'] ?>">
                            <?= $arSubSection['TEXT'] ?>
                        </span></a>
                                            <?
                                            if ($arSubSection['IS_PARENT'] || $arSubSection['MENU_LINKS']):
                                                ?>
                                                <ul class="subsubsection-menu">
                                                    <?
                                                    for ($i = 0; $i < count($arSubSection['CHILDREN']) && $i < $arParams['SUBSECTION_MAX_VIEW']; $i++):
                                                        $arSubSubSection = $arSubSection['CHILDREN'][$i] ?>
                                                        <li class="subsubsection-menu--section">
                                                            <a href="<?= $arSubSubSection['LINK'] ?>"><span
                                                                        class="subsubsection-menu--name image-hover"
                                                                        data-src="<?= $arSubSubSection['PARAMS']['DETAIL_PICTURE'] ?>">
                            <?= $arSubSubSection['TEXT'] ?>
                        </span></a>
                                                        </li>
                                                    <?endfor; ?>
                                                </ul>
                                                <? if (count($arSubSection['CHILDREN']) > $arParams['SUBSECTION_MAX_VIEW']):?>
                                                <a href="<?= $arSubSection['LINK'] ?>">
                                                    <div class="subsection-more">
                                                        Больше...
                                                    </div>
                                                </a>
                                            <?endif;
                                            endif; ?>
                                        </li>
                                    <? endfor; ?>
                                </ul>
                            <? endwhile; ?>
                        </div>
                        <div class="subsection-image--container">
                            <img src="<?= $arResult['TEST_PRODUCT']['IMAGE'] ?>" class="menu-hover-image">
                        </div>
                    </div>
                <? endif; ?>
            </li>
        <? endforeach ?>
    </ul>
    <div class="mobile-catalog--overlay"></div>
    <div class="mobile-catalog--wrapper">
        <ul class="mobile-menu">
            <li class="mobile-section"
                href="tel:<?=$GLOBALS['phone']?>">
                <div id="name-msection-0">
                    <?=$GLOBALS['phone']?>
                </div>
            </li>
            <li class="mobile-section parent"
                id="msection-0"
                data-href="/catalog/">
                <div id="name-msection-0">
                    Каталог <span class="parent-arrow"></span>
                </div>
                <ul class="mobile-submenu">
                    <li class="mobile-nav-back"><span class="back-arrow"></span><span class="back-message">Назад</span>
                    </li>
                    <li class="mobile-nav-title">Каталог</li>
                    <? foreach ($arResult['SECTIONS'] as $arSection): ?>
                        <? if ($arSection['IS_PARENT']): ?>
                            <li class="mobile-section<?= $arSection['IS_PARENT'] ? ' parent' : '' ?>"
                                id="msection-<?= $arSection['ITEM_INDEX'] ?>"
                                data-href="<?= $arSection['LINK'] ?>">
                                <div id="name-msection-<?= $arSection['ITEM_INDEX'] ?>">
                                    <?= $arSection['TEXT'] ?><?= $arSection['IS_PARENT'] ? '<span class="parent-arrow"></span>' : '' ?>
                                </div>
                                <ul class="mobile-submenu">
                                    <li class="mobile-nav-back"><span class="back-arrow"></span><span
                                                class="back-message">Назад</span></li>
                                    <li class="mobile-nav-title"><?= $arSection['TEXT'] ?></li>
                                    <? foreach ($arSection['CHILDREN'] as $arSubSection): ?>

                                        <li class="mobile-section<?= $arSubSection['IS_PARENT'] ? ' parent' : '' ?>"
                                            id="msection-<?= $arSubSection['ITEM_INDEX'] ?>"
                                            data-href="<?= $arSubSection['LINK'] ?>">
                                            <div id="name-msection-<?= $arSubSection['ITEM_INDEX'] ?>">
                                                <?= $arSubSection['TEXT'] ?><?= $arSubSection['IS_PARENT'] ? '<span class="parent-arrow"></span>' : '' ?>
                                            </div>
                                            <? if ($arSubSection['IS_PARENT']): ?>
                                                <ul class="mobile-submenu">
                                                    <li class="mobile-nav-back"><span class="back-arrow"></span><span
                                                                class="back-message">Назад</span></li>
                                                    <li class="mobile-nav-title"><?= $arSubSection['TEXT'] ?></li>
                                                    <? foreach ($arSubSection['CHILDREN'] as $arSubSubSection): ?>
                                                        <li class="mobile-section<?= $arSubSubSection['IS_PARENT'] ? ' parent' : '' ?>"
                                                            id="msection-<?= $arSubSubSection['ITEM_INDEX'] ?>"
                                                            data-href="<?= $arSubSubSection['LINK'] ?>">
                                                            <div id="name-msection-<?= $arSubSubSection['ITEM_INDEX'] ?>">
                                                                <?= $arSubSubSection['TEXT'] ?>
                                                            </div>
                                                        </li>
                                                    <? endforeach; ?>
                                                </ul>
                                            <? endif; ?>
                                        </li>
                                    <? endforeach; ?>
                                </ul>
                            </li>
                        <? endif; ?>
                    <? endforeach ?>
                </ul>
            </li>
            <li class="mobile-section"
                href="/sale/">
                <div>
                    Акции
                </div>
            </li>
            <li class="mobile-catalog__limiter">
                Покупателям
            </li>
            <li class="mobile-section"
                href="/sale/">
                <div>
                    Вход или регистрация
                </div>
            </li>
            <li class="mobile-section"
                href="/sale/">
                <div>
                    Доставка
                </div>
            </li>
            <li class="mobile-section"
                href="/sale/">
                <div>
                    Контакты
                </div>
            </li>
            <li class="mobile-section"
                href="/sale/">
                <div>
                    Чат с оператором
                </div>
            </li>
            <li class="mobile-section"
                href="/sale/">
                <div>
                    Калькуляторы
                </div>
            </li>
            <li class="mobile-catalog__limiter">
                Проекты
            </li>
            <li class="mobile-section"
                href="/sale/">
                <div>
                    Блог
                </div>
            </li>
            <li class="mobile-section"
                href="/sale/">
                <div>
                    Ютуб-канал
                </div>
            </li>
            <li class="mobile-section"
                href="/sale/">
                <div>
                    Оптовикам
                </div>
            </li>
            <li class="mobile-section"
                href="/sale/">
                <div>
                    Франшиза
                </div>
            </li>
        </ul>
    </div>
<? endif ?>

