<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Магазин самогонных аппаратов и товаров для самогоноварения, виноделия и пивоварения. Магазин \"Добрый Жар\" #REGION_NAME_DECLINE_PP#. Доставка по РФ. Гарантии производителя, консультации и помощь в подборе. Телефон: 8 800-600-45-96.");
$APPLICATION->SetPageProperty("title", "Интернет магазин Добрый Жар #REGION_NAME_DECLINE_PP# - самогонные аппараты, комплектующие и ингредиенты");
$APPLICATION->SetTitle("Интернет-магазин \"Добрый Жар\"");
?><span class="section-title mobile"><span class="section-title--name">Специальные акции</span> <a
        href="/sale/" class="section-title--link dj_link">Все →</a> </span>
    <section class="main-sliders"><?
$APPLICATION->IncludeComponent(
	"dj_components:dj.banners",
	"banner.DJ", 
	array(
		"COMPONENT_TEMPLATE" => "banner.DJ",
		"SLIDERS_VIEW" => "1",
		"SCROLL_SPEED" => "0",
		"ENDLESS" => "Y",
		"WIDTH" => "948",
		"HEIGHT" => "350",
		"BANNER_TYPE" => "main",
		"BANNER_ID" => "main",
		"MIN-SLIDE-WIDTH" => "250",
		"CONTROLS" => "center"
	),
	false
);?>
        <?

$APPLICATION->IncludeComponent(
	"dj_components:dj.banners",
	"banner.DJ", 
	array(
		"COMPONENT_TEMPLATE" => "banner.DJ",
		"SLIDERS_VIEW" => "1",
		"SCROLL_SPEED" => "0",
		"ENDLESS" => "Y",
		"WIDTH" => "246",
		"HEIGHT" => "350",
		"BANNER_TYPE" => "filter",
		"BANNER_ID" => "filter",
		"MIN-SLIDE-WIDTH" => "200",
		"CONTROLS" => "HIDDEN"
	),
	false
); ?>
    </section>
    <section class="popular-categories"><span class="section-title"> <span class="section-title--name">Популярные категории</span> <a
                    href="/catalog/samogonovarenie/" class="section-title--link dj_link">Каталог →</a> </span>
        <? $APPLICATION->IncludeComponent(
	"dj_components:dj_banners", 
	"banner.DJ", 
	array(
		"BANNER_ID" => "popular",
		"BANNER_TYPE" => "popular",
		"CATEGORY_ID" => array(
			0 => "18",
			1 => "23",
			2 => "25",
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
); ?>
        <? $APPLICATION->IncludeComponent(
	"dj_components:dj_banners", 
	"banner.DJ", 
	array(
		"BANNER_ID" => "popular_2",
		"BANNER_TYPE" => "popular_2",
		"CATEGORY_ID" => array(
			0 => "29",
			1 => "45",
			2 => "47",
		),
		"COMPONENT_TEMPLATE" => "banner.DJ",
		"CONTROLS" => "sides",
		"ENDLESS" => "N",
		"HEIGHT" => "150",
		"MIN-SLIDE-WIDTH" => "250",
		"SCROLL_SPEED" => "0",
		"SLIDERS_VIEW" => "3",
		"WIDTH" => "1200"
	),
	false
); ?> </section>
<? $APPLICATION->IncludeComponent(
	"dj_components:dj.resources", 
	".default", 
	array(
		"SECTION_NUMBER" => "4",
		"SECTION_NAME_1" => "Калькулятор",
		"SECTION_TEXT_1" => "Винокура",
		"SECTION_BACKGROUND_1" => "/upload/medialibrary/23b/lwxr7xeo57ws2pvmathn5fllsy9e3a8b.png",
		"SECTION_NAME_2" => "Рецепты",
		"SECTION_TEXT_2" => "Добрый Жар",
		"SECTION_BACKGROUND_2" => "/upload/medialibrary/f44/z2oukx5ampkw6wgvewm6dfkgb8r9lgmw.png",
		"SECTION_NAME_3" => "Рассрочка",
		"SECTION_TEXT_3" => "Без процентов",
		"SECTION_BACKGROUND_3" => "/upload/medialibrary/383/3do3djuf1bdoep5tf8ljweubgka9kwl6.png",
		"SECTION_NAME_4" => "Гарантия",
		"SECTION_TEXT_4" => "Качества",
		"SECTION_BACKGROUND_4" => "/upload/medialibrary/862/qyelsm4n8glert523erwz96czvz6lz7y.png",
		"COMPONENT_TEMPLATE" => ".default",
		"SECTION_LINK_1" => "https://blog.dobriy-jar.ru/calculators/",
		"SECTION_LINK_2" => "https://blog.dobriy-jar.ru/samogonovarenie/",
		"SECTION_LINK_3" => "https://dobriy-jar.ru/installment/",
		"SECTION_LINK_4" => "https://dobriy-jar.ru/guaranty/"
	),
	false
);?>
    <section class="hit"> <span class="section-title">
            <span class="section-title--name">Хит продаж</span> <a href="/catalog/samogonnye_apparaty/" class="section-title--link dj_link">Показать все →</a> </span>
        <? $APPLICATION->IncludeComponent(
            "dj_components:dj_banners",
            "banner.DJ",
            array(
                "BANNER_ID" => "hit",
                "BANNER_TYPE" => "hit",
                "COMPONENT_TEMPLATE" => "banner.DJ",
                "CONTROLS" => "sides",
                "ENDLESS" => "Y",
                "HEIGHT" => "250",
                "MIN-SLIDE-WIDTH" => "250",
                "SCROLL_SPEED" => "0",
                "SLIDERS_VIEW" => "3",
                "WIDTH" => "1200"
            )
        ); ?> </section>
    <section class="blog"> <span class="section-title"> <span class="section-title--name">Блог</span>
            <a href="https://blog.dobriy-jar.ru/" class="section-title--link dj_link">Показать все →</a> </span>
        <? $APPLICATION->IncludeComponent(
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
		"WIDTH" => "1000",
		"ARTICLE_ID" => array(
			0 => "6240",
			1 => "6217",
			2 => "6225",
		)
	),
	false
); ?> </section>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");

?>