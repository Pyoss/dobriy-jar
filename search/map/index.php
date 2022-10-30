<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Карта сайта");

$APPLICATION->IncludeComponent(
	"dj_components:dj_banners", 
	"banner.DJ", 
	array(
		"COMPONENT_TEMPLATE" => "banner.DJ",
		"SLIDERS_VIEW" => "1",
		"SCROLL_SPEED" => "0",
		"ENDLESS" => "Y",
		"WIDTH" => "1200",
		"HEIGHT" => "444",
		"BANNER_TYPE" => "main",
		"BANNER_ID" => "main",
		"MIN-SLIDE-WIDTH" => "250",
		"CONTROLS" => "center"
	),
	false); ?>
    <section class="popular-categories"> <span class="section-title"> <span class="section-title--name">Популярные категории</span> <a href="#" class="section-title--link dj_link">Каталог →</a> </span>
        <?$APPLICATION->IncludeComponent(
	"dj_components:dj_banners", 
	"banner.DJ", 
	array(
		"BANNER_ID" => "popular",
		"BANNER_TYPE" => "popular",
		"CATEGORY_ID" => array(
			0 => "18",
			1 => "27",
			2 => "81",
			3 => "101",
		),
		"COMPONENT_TEMPLATE" => "banner.DJ",
		"CONTROLS" => "sides",
		"ENDLESS" => "N",
		"HEIGHT" => "250",
		"MIN-SLIDE-WIDTH" => "250",
		"SCROLL_SPEED" => "0",
		"SLIDERS_VIEW" => "3",
		"WIDTH" => "1200"
	),
	false
);?> </section>
    <section class="blog"> <span class="section-title"> <span class="section-title--name">А знаете ли вы?</span>
            <a href="#" class="section-title--link dj_link">Показать все →</a> </span>
    <?$APPLICATION->IncludeComponent(
        "dj_components:dj_banners",
        "banner.DJ",
        array(
            "BANNER_ID" => "blog",
            "BANNER_TYPE" => "blog",
            "COMPONENT_TEMPLATE" => "banner.DJ",
            "CONTROLS" => "HIDDEN",
            "ENDLESS" => "Y",
            "HEIGHT" => "400",
            "MIN-SLIDE-WIDTH" => "100",
            "SCROLL_SPEED" => "0",
            "SLIDERS_VIEW" => "1",
            "WIDTH" => "1000"
        ),
        false
    );?> </section> <section class="hit"> <span class="section-title"> <span class="section-title--name">Хит продаж</span> <a href="#" class="section-title--link dj_link">Показать все →</a> </span>
    <?$APPLICATION->IncludeComponent(
        "dj_components:dj_banners",
        "banner.DJ",
        Array(
            "BANNER_ID" => "hit",
            "BANNER_TYPE" => "hit",
            "COMPONENT_TEMPLATE" => "banner.DJ",
            "CONTROLS" => "center",
            "ENDLESS" => "Y",
            "HEIGHT" => "250",
            "MIN-SLIDE-WIDTH" => "250",
            "SCROLL_SPEED" => "0",
            "SLIDERS_VIEW" => "3",
            "WIDTH" => "1200"
        )
    );?> </section>
        <?$APPLICATION->IncludeComponent(
	"dj_components:dj.resources", 
	".default", 
	array(
		"SECTION_NUMBER" => "4",
		"SECTION_NAME_1" => "Алко",
		"SECTION_TEXT_1" => "Калькулятор",
		"SECTION_BACKGROUND_1" => "/upload/medialibrary/51b/4tsng3ygu3p4n6o8ini8o44vhcv2pimt.png",
		"SECTION_NAME_2" => "Рецепты",
		"SECTION_TEXT_2" => "Добрый Жар",
		"SECTION_BACKGROUND_2" => "/upload/medialibrary/6c3/ahq3daafieyj8y8pidjjxmf11pbjr151.png",
		"SECTION_NAME_3" => "Рассрочка",
		"SECTION_TEXT_3" => "Без процентов",
		"SECTION_BACKGROUND_3" => "/upload/medialibrary/925/8wcdmmfp5waj3vv4i7z1vaqdyn64z18q.png",
		"SECTION_NAME_4" => "Гарантия",
		"SECTION_TEXT_4" => "Качества",
		"SECTION_BACKGROUND_4" => "/upload/medialibrary/3ec/ll9auv1ildlilvx4nrpteiu2yb52ylsp.png",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);

        $APPLICATION->IncludeComponent(
	"dj_components:main.feedback", 
	"dj_feedback", 
	array(
		"EMAIL_TO" => "igork@dobriy-jar.ru",
		"EVENT_MESSAGE_ID" => array(
			0 => "7",
		),
		"OK_TEXT" => "Спасибо, ваше сообщение принято.",
		"REQUIRED_FIELDS" => array(
			0 => "NAME",
			1 => "EMAIL",
			2 => "PHONE",
			3 => "CITY",
		),
		"USE_CAPTCHA" => "N",
		"COMPONENT_TEMPLATE" => "dj_feedback",
		"BACKGROUND_FILE" => "/upload/medialibrary/988/6lftgi0idkcxwrhmhg7cpjhz2zj11kdw.png"
	),
	false
);?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>