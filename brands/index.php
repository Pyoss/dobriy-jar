<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Бренды и торговые марки товаров магазина самогонных аппаратов и товаров для самогоноварения, виноделия и пивоварения. Магазин \"Добрый Жар\" #REGION_NAME_DECLINE_PP#. Телефон: 8 800-600-45-96.");
$APPLICATION->SetPageProperty("title", "Бренды магазина \"Добрый Жар\"");
$APPLICATION->SetTitle("Бренды");
$APPLICATION->IncludeComponent(
    "bitrix:breadcrumb",
    ".default",
    array(
        "START_FROM" => "0",
        "PATH" => "",
        "SITE_ID" => "s1",
        "COMPONENT_TEMPLATE" => ".default"
    ),
    false
);
$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"dj.brands", 
	array(
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "6",
		"IBLOCK_TYPE" => "news",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"LIST_ACTIVE_DATE_FORMAT" => "j F Y",
		"LIST_FIELD_CODE" => array(
			0 => "DETAIL_PICTURE",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PREVIEW_TRUNCATE_LEN" => "",
		"SEF_MODE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "N",
		"USE_PERMISSIONS" => "N",
		"USE_RATING" => "N",
		"USE_REVIEW" => "N",
		"USE_RSS" => "N",
		"USE_SEARCH" => "N",
		"USE_SHARE" => "N",
		"COMPONENT_TEMPLATE" => "dj.brands",
		"SEF_FOLDER" => "/brands/",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "#ELEMENT_CODE#/",
		)
	),
	false
);?><section class="hit"> <span class="section-title"> <span class="section-title--name">Хит продаж</span> <a href="#" class="section-title--link dj_link">Показать все →</a> </span>
<?$APPLICATION->IncludeComponent(
	"dj_components:dj_banners",
	"banner.DJ",
	Array(
		"BANNER_ID" => "hit",
		"BANNER_TYPE" => "hit",
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
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"SECTION_BACKGROUND_1" => "/upload/medialibrary/23b/lwxr7xeo57ws2pvmathn5fllsy9e3a8b.png",
		"SECTION_BACKGROUND_2" => "/upload/medialibrary/f44/z2oukx5ampkw6wgvewm6dfkgb8r9lgmw.png",
		"SECTION_BACKGROUND_3" => "/upload/medialibrary/383/3do3djuf1bdoep5tf8ljweubgka9kwl6.png",
		"SECTION_BACKGROUND_4" => "/upload/medialibrary/862/qyelsm4n8glert523erwz96czvz6lz7y.png",
		"SECTION_LINK_1" => "https://blog.dobriy-jar.ru/calculators/",
		"SECTION_LINK_2" => "https://blog.dobriy-jar.ru/samogonovarenie/",
		"SECTION_LINK_3" => "https://dobriy-jar.ru/installment/",
		"SECTION_LINK_4" => "https://dobriy-jar.ru/guaranty/",
		"SECTION_NAME_1" => "Алко",
		"SECTION_NAME_2" => "Рецепты",
		"SECTION_NAME_3" => "Рассрочка",
		"SECTION_NAME_4" => "Гарантия",
		"SECTION_NUMBER" => "4",
		"SECTION_TEXT_1" => "Калькулятор",
		"SECTION_TEXT_2" => "Добрый Жар",
		"SECTION_TEXT_3" => "Без процентов",
		"SECTION_TEXT_4" => "Качества"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>