<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
define("AUTH", true);
$APPLICATION->SetTitle("Title");
?><?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.forgotpasswd",
	"dj.forgotpasswd",
	Array(
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>