<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => 'Уникальные бонусы',
	"DESCRIPTION" => 'Назначение уникальных бонусов при клике',
	"SORT" => 20,
	"TYPE" => "mail",
	"CACHE_PATH" => "Y",
    "ICON" => "/images/viewed_products.gif",
    "ID" => "content",
    "CHILD" => array(
        "ID" => "news",
        "NAME" => 'Бонусная система',
        "SORT" => 5,
    ),
);

?>