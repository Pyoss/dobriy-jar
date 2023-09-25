<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Магазин самогонных аппаратов и товаров для самогоноварения, виноделия и пивоварения. Магазин \"Добрый Жар\" #REGION_NAME_DECLINE_PP#. Доставка по РФ. Гарантии производителя, консультации и помощь в подборе. Телефон: 8 800-600-45-96.");
$APPLICATION->SetPageProperty("title", "Добрый Жар #REGION_NAME_DECLINE_PP#: интернет-магазин самогонных аппаратов, комплектующих и ингредиентов");
$APPLICATION->SetTitle("Интернет-магазин \"Добрый Жар\" #REGION_NAME_DECLINE_PP# ");
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
			0 => "18",
			1 => "24",
			2 => "25",
			3 => "26",
			4 => "29",
			5 => "45",
			6 => "81",
			7 => "96",
			8 => "101",
			9 => "102",
			10 => "134",
		),
		"BACKGROUND" => "/upload/medialibrary/2df/orcynmyfet9ey0byuma7j451uv612ep4.png"
	),
	false
); ?>

<? $APPLICATION->IncludeComponent(
    "dj-comp:dj.hit",
    "alt",
    array()
); ?>
<? $APPLICATION->IncludeComponent(
    "dj-comp:dj.new",
    "alt",
    array()
); ?>
<? $APPLICATION->IncludeComponent(
	"dj-comp:dj.resources", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"BACKGROUND_IMAGE" => "/upload/medialibrary/fc2/7swj5u0djux7z0gbaahrgu2pjzsdf5v0.png",
		"BACKGROUND_IMAGE_MOBILE" => "/upload/medialibrary/e62/yrw3b9rua7c91sotz7d4h28r7l6l0c4u.png",
		"IMAGE_CALC" => "/upload/medialibrary/01c/nbr7vymrqm4r8rjpyqgaxd4cmiv77151.png",
		"IMAGE_BRANDS" => "/upload/medialibrary/8e9/rhndbi7rx78elxix7eweu4p6htcd2zt9.png",
		"IMAGE_SHIPMENT" => "/upload/medialibrary/e19/s6ubycguvxixeyhozhk6ekwcr4t53mhh.png",
		"IMAGE_INSTALLMENT" => "/upload/medialibrary/bdd/p5iu93jppxnenhsxce4vjxe92sb662be.png",
		"BACKGROUND_RES" => "/upload/medialibrary/e62/yrw3b9rua7c91sotz7d4h28r7l6l0c4u.png"
	),
	false
); ?>
<? $APPLICATION->IncludeComponent(
	"dj-comp:dj.blog-links", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CATEGORY_ID" => array(
			0 => "6217",
			1 => "6225",
			2 => "7259",
			3 => "7611",
		)
	),
	false
); ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");

?>