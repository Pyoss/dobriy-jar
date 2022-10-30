<?

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;
$aMenuLinksExt = array();

$MENU = $USER -> GetID() ? $USER -> GetFirstName() : 'Войти';
$aMenuLinks[] = array($MENU, '/personal/', array(), array(), '');
?>