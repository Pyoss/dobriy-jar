<?php
/* Получение цены по артикулу. Для лендингов и интеграций. */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$articles = explode(',', $_GET['articles']);
/* ищем товар по артикулу */
if (!$articles){
    die();
}
$arJson = [];
foreach ($articles as $article ){

    $rsElementProperties = \Bitrix\Iblock\ElementPropertyTable::getList(
        array(
            'filter' => ['IBLOCK_PROPERTY_ID' => array(9, 20), 'VALUE' => $article]
        )
    );

    while ($arProperty = $rsElementProperties -> fetch()){
        $rsElement = \Bitrix\Iblock\ElementTable::getById(
            $arProperty['IBLOCK_ELEMENT_ID']
        )->fetch();
        $arJsonData['name'] = $rsElement['NAME'];
        $arJsonData['article'] = $article;
        $arJsonData['price']  = CCatalogProduct::GetOptimalPrice($arProperty['IBLOCK_ELEMENT_ID'])['DISCOUNT_PRICE'];
        $arJson[] = $arJsonData;
    }
}
echo json_encode($arJson);
/* ищем тп по артикулу */
/* получаем актуальные цены и шифруем ответ в json
    если не найдено - отдаем ошибку
*/

