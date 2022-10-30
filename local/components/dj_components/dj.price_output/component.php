<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

include_once $_SERVER['DOCUMENT_ROOT'] . $componentPath . '/SimpleXLSXGen.php';
include_once $_SERVER['DOCUMENT_ROOT'] . $componentPath . '/CatalogOutput.php';
use Shuchkin\SimpleXLSXGen;
use DJScripts\CatalogOutput;

$Coutput = new CatalogOutput();
$Coutput -> getAllProducts();
$products[] = ['Артикул', 'Название', 'Цена', 'Раздел', 'Текст', 'html', 'Картинка', 'Галерея'];
foreach ($Coutput -> getProductArray() as $product){
    $products[] = array($product['ARTNUMBER'], $product['NAME'], $product['CATALOG_PRICE_2'], $product['SECTION_NAME'], $product['SEARCHABLE_CONTENT'], $product['DETAIL_TEXT'], $product['DETAIL_PICTURE_URL'], implode(';', $product['MORE_PHOTO']));
}

$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $products);
$xlsx->downloadAs('price.xlsx'); // or downloadAs('books.xlsx') or $xlsx_content = (string) $xlsx
exit();