<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Магазин самогонных аппаратов и товаров для самогоноварения, виноделия и пивоварения. Магазин \"Добрый Жар\" #REGION_NAME_DECLINE_PP#. Доставка по РФ. Гарантии производителя, консультации и помощь в подборе. Телефон: 8 800-600-45-96");
$APPLICATION->SetPageProperty("title", "Адрес магазина Добрый Жар #REGION_NAME_DECLINE_PP#");
$APPLICATION->SetTitle("Добрый Жар #REGION_NAME_DECLINE_PP#");
?>
<div class="center-content">
<?php
if ($_SESSION['GEODJ']['DETAIL_TEXT'] !== ''){
    echo $_SESSION['GEODJ']['DETAIL_TEXT'];
} else {
    $res =  \Bitrix\Iblock\ElementTable::getList(
        array(
            'filter' => array('IBLOCK_ID' => 4, 'CODE' => 'default_domain'),
            'select' => array('ID', 'NAME', 'DETAIL_TEXT')
        )
    );
    echo $res ->fetch()['DETAIL_TEXT'];
};
?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php")?>