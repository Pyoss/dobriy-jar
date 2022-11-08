<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
define("HIDE_SIDEBAR", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");?>

    <div class="container-404">
        <div class="container-404__code"><span>404</span></div>
        <div class="container-404__text">
            <span>К сожалению, такой страницы не существует.<br> Возможно, вы неправильно набрали адрес.
            </span>
        </div>
        <img src='/upload/404.png' class="container-404__img">
        <div class="container-404__quote"><span><i>
            «Чтобы капал самогон мне в рот<br> днем и ночью круглый год!»
        </span></i></div>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
