<?php

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock;

class Tab1C
{
    public function getTabList($elementInfo)
    {
        $request = Context::getCurrent()->getRequest();
        $addTabs = ($request['action'] != 'copy' &&
            in_array($elementInfo['IBLOCK']['CODE'], ['products', 'offers']));
        return $addTabs ? [
            [
                "DIV" => 'maximaster_some_tab',
                "SORT" => PHP_INT_MAX,
                "TAB" => 'Соединение 1С',
                "TITLE" => 'Соединение 1С',
            ],
        ] : null;
    }

    public function showTabContent($div, $elementInfo, $formData)
    {
        Loader::includeModule("highloadblock");
        $hlbl = 3; // Таблица соответствия GUID

        $hlblock = Highloadblock\HighloadBlockTable::getById($hlbl)->fetch();
        $entity = Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $foundData = false;
        $artnumber = CIBlockElement::GetProperty($elementInfo['IBLOCK']['ID'],
            $elementInfo['ID'],
            array("sort" => "asc"),
            array("CODE" => "ARTNUMBER"))->fetch();
        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "filter" => array(
                "UF_IBLOCK_ID" => $elementInfo['IBLOCK']['ID'],
                "UF_ELEMENT_ID" => $elementInfo['ID']
            )
        ));
        while ($arData = $rsData->Fetch()) {
            $foundData = $arData;
        }
        if ($foundData) {
            ?>
            <span style="font-size: 16px; line-height: 30px">Название товара 1С: <span
                    style="padding: 5px;"> <?= $foundData['UF_1C_PRODUCT_NAME'] ?></span>
            <br>
            <span style="font-size: 16px; line-height: 30px">Идентификатор: <span
                        style="padding: 5px;"><?= $foundData['UF_GUID'] ?></span></span>
            <?php
        } else {
            ?><span>Связь не установлена</span>
            <?
        }
        ?><br><br>
    <button id="update-guid" data-id="<?= $elementInfo['ID']?>"
            data-iblock-id="<?=$elementInfo['IBLOCK']['ID']?>"
            data-article="<?= $artnumber['VALUE'] ?>">
        Обновить связь по артикулу <?= $artnumber['VALUE'] ?></button><?
    }

    public function check()
    {
        return true;
    }

    public function action()
    {
        return true;
    }
}