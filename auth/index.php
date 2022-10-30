<?
require($_SERVER["DOCUMENT_ROOT"]."bitrix/header.php");
define("AUTH", true);
global $USER;
if ($_GET['login'] == 'yes' && $USER -> GetID()) {
    localRedirect('/personal/');
}
$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form",
	"dj.auth",
	Array(
		"FORGOT_PASSWORD_URL" => "/auth/forgot_password.php",
		"PROFILE_URL" => "/personal/",
		"REGISTER_URL" => "/auth/registration.php",
		"SHOW_ERRORS" => "Y"
	)
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
