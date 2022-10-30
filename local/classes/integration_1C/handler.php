<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
$CLASSES_DIR = $_SERVER['DOCUMENT_ROOT'] . '/local/classes/';
require_once $CLASSES_DIR . 'integration_1C/Integration1C.php';


//-----------------------------------------------------
// список хэндлеров и их функций
$integration = Integration1C::getInstance();

$integration -> Add1CHandler('catalog', function () {
    $json = file_get_contents('php://input');
    $arStoreProducts = json_decode($json, true)['products'];
    $strg = file_get_contents('log_packet.txt');
    file_put_contents('log_packet.txt', $strg . '\n' . $json);
});

//setPrice(317, array('RetailPrice' => 3000, 'BasePrice'=>2000, 'StockPrice'=>4000));

$integration -> RequestHandler();
//-----------------------------------------------------

function updateStores1C($arStoreProducts, $source){
//    $arAllStores = getStoresAr();
    if ($source == "UNF") {
        updateStoreProducts(false, $arStoreProducts);
    }
}

function updateStoreProducts($storeID, $arStoreProducts){
    // Установление количества товаров в складе storeId

    foreach($arStoreProducts as $arSProduct){

        $productID = getIDfromGUID($arSProduct['GUID']);
        if ($productID){
            setStoreAmount($productID, $storeID, $arSProduct);
            if (!$storeID){
                setPrice($productID, $arSProduct);
            }
        }
    }
}

function setStoreAmount($productID, $storeID, $arSProduct){
    $amount = (int)$arSProduct['available'];
    if ($amount < 0) $amount = 0;
    if ($arSProduct['spz'] == 1) $amount = 100;
    if (!$storeID){
        $config = array(
            "select" => array("ID"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID"=>$productID)
        );

        $res = \Bitrix\Catalog\Model\Product::getList($config);
        $arID = $res->fetch();

        if ($arID){
            \Bitrix\Catalog\Model\Product::update($arID['ID'], array("QUANTITY" => $amount));
            print_r('updated');
        } else {
            $arStoreProduct = array(
                "ID" => $productID,
                "QUANTITY" => $amount);
            \Bitrix\Catalog\Model\Product::add($arStoreProduct);
            print_r('added');
        }
    }
}

function setPrice($productID, $arSProduct){
    //В случае основного склада обновляем цены
    $arPriceValues = array(
        1 => $arSProduct['StockPrice'],
        2 => $arSProduct['RetailPrice'],
        3 => $arSProduct['TradePrice']
    );

    $rsPrices = Bitrix\Catalog\PriceTable::getList([
        "select" => ["*"],
        "filter" => [
            "=PRODUCT_ID" => $productID,
        ]
    ]);

    while ($row = $rsPrices->fetch()) {
        $arPrices[$row['CATALOG_GROUP_ID']] = $row['ID'];
    }
    foreach ($arPriceValues as $priceGroupID => $value){
        $arPriceDetail = Array(
            "PRODUCT_ID" => $productID,
            "CATALOG_GROUP_ID" => $priceGroupID,
            "PRICE" => (int)$value,
            "CURRENCY" => 'RUB'
        );
        if (key_exists($priceGroupID, $arPrices)) {
            // Обновление цены
            $priceID = $arPrices[$priceGroupID];
            Bitrix\Catalog\Model\Price::update($priceID, $arPriceDetail);
        }
        else {
            Bitrix\Catalog\Model\Price::add($arPriceDetail);
        }
    }
}

function getIDfromGUID($GUID){
    $config = array(
        "filter" => array("PROPERTY_GUID"=>$GUID),
        "select" => array("ID")

    );
    $rs = CIBlockElement::GetList(
        array(),
        $config['filter'],
        false,
        false,
        $config['select'],
    );
    $row = $rs -> fetch();
    return $row['ID'];
}


