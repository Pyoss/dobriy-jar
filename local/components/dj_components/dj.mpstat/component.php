<?php
if (!$_GET['ajax']){
    $this->IncludeComponentTemplate();
} else {
    $ids = explode(',', $_GET['ids']);
    $date = $_GET['date'];
    $marketplace = $_GET["mplc"];
    $APIkey = '61b73683dbef24.741267119d570f8c760e84977f7a9f31aa0689ea';
    foreach ($ids as $id){
        $SKU = getSKU($marketplace, (int)$id);
        $saleData = parseData(getSkuMonthData($marketplace, (int)$id, $date));
        $responseArray[] = ['SKU' => $SKU, 'saleData' => $saleData];
    }
    echo "<json>";
    echo json_encode($responseArray);
    echo "</json>";
    DJmain::displayString($responseArray);
}

function requestAPI($request_string){
    $curl = curl_init($request_string);
    curl_setopt($curl, CURLOPT_URL, $request_string);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "X-Mpstats-TOKEN: 61b73683dbef24.741267119d570f8c760e84977f7a9f31aa0689ea",
        "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);
    return json_decode($resp, true);
}

function getSkuMonthData($marketplace, int $skuID, $date){
    $new_date = date("Y-m-d", strtotime ( '-1 month' , strtotime ( $date ) )) ;
    $request_string = "https://mpstats.io/api/".$marketplace."/get/item/".$skuID."/sales?d1=".$new_date."&d2=".$date;
    return requestAPI($request_string);
}

function getSKU($marketplace, int $skuID){
    $request_string = "https://mpstats.io/api/".$marketplace."/get/item/".$skuID;
    return requestAPI($request_string);
}

function parseData($SKUdataArray){
    $SKUdata = [
        "final_price" => [
            "name" => "Конечная цена",
            "earliest" => 0,
            "latest" => 0,
        ],
        "price" => [
            "name" => "Цена",
            "earliest" => 0,
            "latest" => 0,
        ],
        "rating" => [
            "name" => "Рейтинг",
            "earliest" => 0,
            "latest" => 0,
        ],
        "comments" => [
            "name" => "Комментарии",
            "earliest" => 0,
            "latest" => 0,
        ],
        "sales" => [
            "name" => "Продажи",
            "fixed" => true,
            "value" => 0
        ],
        "income" => [
            "name" => "Выручка",
            "fixed" => true,
            "value" => 0
        ]
    ];
    $SKUdataArray = array_reverse($SKUdataArray);
    foreach ($SKUdataArray as $dailyData){
        $SKUdata["sales"]["value"] = $SKUdata["sales"]["value"] + $dailyData["sales"];
        $SKUdata["income"]["value"] = $SKUdata["income"]["value"] + ($dailyData["sales"] * $dailyData["final_price"]);
        foreach ($SKUdata as $type => $values){
            if ($dailyData[$type] == 0|| $values["fixed"]){
                continue;
            }
            if ($values["earliest"] == 0){
                $SKUdata[$type]["earliest"] = $dailyData[$type];
            }
            $SKUdata[$type]["latest"] = $dailyData[$type];
        }
    }
    return $SKUdata;
}