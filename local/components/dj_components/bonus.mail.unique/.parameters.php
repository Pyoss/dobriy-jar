<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"BONUS_AMOUNT" => array(
			"PARENT" => "BASE",
			"NAME" => 'Количество бонусов',
			"TYPE" => "INTEGER",
			"DEFAULT" => "500"
		),
	),
);

?>