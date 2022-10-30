<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $USER;
if($_GET['logout']){
    $USER->Logout();
    localRedirect('/');
}
$arResult['USER']['NAME'] = $USER->GetFirstName() ?: $USER->GetLogin();
$arResult['USER']['LAST_NAME'] = $USER->GetLastName();
if ($ar = CSaleUserAccount::GetByUserID( $USER->GetID(), "RUB")) {
    $arResult['USER']['BONUS_ACCOUNT'] = $ar;
} else {
    $arResult['USER']['BONUS_ACCOUNT']['CURRENT_BUDGET'] = 0;
}

