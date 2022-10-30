<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
define("AUTH", true);
$APPLICATION->SetTitle("Title");
?><?$APPLICATION->IncludeComponent(
    "bitrix:system.auth.changepasswd",
    "",
    Array(
    )
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>