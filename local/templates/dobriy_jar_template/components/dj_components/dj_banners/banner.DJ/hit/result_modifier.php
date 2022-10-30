<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();}

// Getting products with marked property (hit)
$rsProperty = \Bitrix\Iblock\ElementPropertyTable::getList(
    array('filter' => array('IBLOCK_PROPERTY_ID' => array(45, 46), 'VALUE' => array(60, 62)),
        'select' => array('IBLOCK_ELEMENT_ID'))
);

while($property = $rsProperty -> Fetch()){
    $elementIDs[]= $property['IBLOCK_ELEMENT_ID'];
}

if ($elementIDs){

    // Getting primary fields
    $rsElement = \Bitrix\Iblock\ElementTable::getList(
        array('filter' => array("ID" => $elementIDs),
                'select' => array("NAME", "PREVIEW_PICTURE", "ID", "IBLOCK_ID")));

    while($element = $rsElement -> Fetch()){

        // Getting prices
        $price = \Bitrix\Catalog\PriceTable::getList(
            array(
                'filter' => array("PRODUCT_ID" => $element['ID'], "CATALOG_GROUP_ID" => 2),
                'select' => array('PRICE')
            )
        ) -> Fetch()['PRICE'];

        // Getting product type
        // (Checking if product is an offer)
        $parent_element_id =
            ($element['IBLOCK_ID'] == 2) ? $element['ID'] : CCatalogSku::GetProductInfo($element['ID'],
                $element['IBLOCK_ID']);

        $product_type = \Bitrix\Iblock\ElementPropertyTable::getList(
            array(
                'filter' => array("IBLOCK_ELEMENT_ID" => $parent_element_id,
                                  'IBLOCK_PROPERTY_ID' => 39),
                'select' => array("VALUE")
            )) -> Fetch()['VALUE'];
        $element['PRICE'] = $price;
        $element['TYPE'] = $product_type;
        $element['PICTURE'] = CFile::GetPath($element['PREVIEW_PICTURE']);
        $element['CODE'] = DJMain::getFullUrl($element['ID']);
        $element['VIEW_NAME'] = DJMain::replaceProductType($element['TYPE'], $element['NAME']);
        $elements[] = $element;
    }
}
$arResult['BANNER_DATA'] = $elements;

