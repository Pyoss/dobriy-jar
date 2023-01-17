<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Магазин самогонных аппаратов и товаров для самогоноварения, виноделия и пивоварения. Магазин \"Добрый Жар\" #REGION_NAME_DECLINE_PP#. Доставка по РФ. Гарантии производителя, консультации и помощь в подборе. Телефон: 8 800-600-45-96.");
$APPLICATION->SetPageProperty("title", "Интернет магазин Добрый Жар #REGION_NAME_DECLINE_PP# - самогонные аппараты, комплектующие и ингредиенты");
$APPLICATION->SetTitle("Интернет-магазин \"Добрый Жар\"");
?>
    <section class="sliders">
        <div class="sliders__container">
            <? $APPLICATION->IncludeComponent(
                "dj-comp:dj.fadeout_banner",
                ".default",
                array(),
                false
            ); ?>
            <? $APPLICATION->IncludeComponent(
                "dj-comp:dj.fadeout_banner",
                "filter",
                array(),
                false
            ); ?>
        </div>
    </section>
<? $APPLICATION->IncludeComponent(
    "dj-comp:dj.popular_categories",
    ".default",
    array(
        "COMPONENT_TEMPLATE" => ".default",
        "CATEGORY_ID" => array(
            0 => "39",
            1 => "40",
            2 => "41",
            3 => "42",
            4 => "43",
            5 => "44",
        )
    ),
    false
); ?>

<? $APPLICATION->IncludeComponent(
    "dj-comp:dj.hit",
    ".default",
    array()
); ?>
<? $APPLICATION->IncludeComponent(
    "dj-comp:dj.new",
    ".default",
    array()
); ?>
<? $APPLICATION->IncludeComponent(
    "dj-comp:dj.resources",
    ".default",
    array(
        "COMPONENT_TEMPLATE" => ".default",
        "BACKGROUND_IMAGE" => "/upload/medialibrary/fc2/7swj5u0djux7z0gbaahrgu2pjzsdf5v0.png",
        "BACKGROUND_IMAGE_MOBILE" => "/upload/medialibrary/fc2/7swj5u0djux7z0gbaahrgu2pjzsdf5v0.png",
        "IMAGE_CALC" => "/upload/medialibrary/c15/vwxbb6cag9l6u73u2qmc1i4j7w8n2w2d.png",
        "IMAGE_BRANDS" => "/upload/medialibrary/f1a/mknrtkn9yd1o6xfr1yyq0995ct1lam3k.png",
        "IMAGE_SHIPMENT" => "/upload/medialibrary/a91/j07sj79nuuxt78zbehxoztwlwmivorue.png",
        "IMAGE_INSTALLMENT" => "/upload/medialibrary/07c/j35ec46c2a4wku31ca7c8eueemtdaqt8.png"
    ),
    false
); ?>
<? $APPLICATION->IncludeComponent(
    "dj-comp:dj.blog-links",
    ".default",
    array(
    ),
    false
); ?>

<? $APPLICATION->IncludeComponent(
    "dj-comp:dj.callback",
    ".default",
    array(
    ),
    false
); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>