<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Service\GeoIp;
use \Bitrix\Iblock\ElementPropertyTable;

/***
 * Делаем запрос к GEOIP, определяем рекоммендуемый и текущий город в зависимости от подключенного инфоблока
 */

$arParams['REGION_NAME_DECLINE_PP'] = 70;
$arParams['REGION_NAME_DECLINE_IP'] = 71;

function getInfoByIp(): array
{
    $ipAddress = GeoIp\Manager::getRealIp();
    $result = GeoIp\Manager::getDataResult($ipAddress, "ru");
    if ($result){
        $geoData = $result->getGeoData();
        $city_name = $geoData->cityName;
        $coords = array('latitude' => $geoData->latitude, 'longitude' => $geoData->longitude);
        return array(
            'IP_CITY_NAME' => $city_name,
            'IP_COORDINATES' => $coords
        );}
    return array(
    'IP_CITY_NAME' => '',
    'IP_COORDINATES' => ''
);
    }

$arResult['geoIpData'] = getInfoByIp();
$resGeo = \Bitrix\Iblock\ElementTable::getList(
    array(
        'filter' => array('IBLOCK_ID' => $arParams['IBLOCK_ID']),
        'select' => array('ID', 'NAME', 'DETAIL_TEXT')
    )
);

while ($arGeo = $resGeo -> fetch()){
    $resDomain = \Bitrix\Iblock\ElementPropertyTable::getList(array(
        'filter' => array('IBLOCK_PROPERTY_ID' => array($arParams['DOMAIN_PROP_ID'],
                                                        70, 71), 'IBLOCK_ELEMENT_ID' => $arGeo['ID']),
        'select' => array('*')
    ));
    $arGeo['REPLACE_VALUES']['#REGION_NAME_DECLINE_IP#'] = false;
    $arGeo['REPLACE_VALUES']['#REGION_NAME_DECLINE_PP#'] = false;

    while ($arDomain = $resDomain -> fetch()){
        switch ($arDomain['IBLOCK_PROPERTY_ID']){
            case $arParams['DOMAIN_PROP_ID']:
                $arGeo['DOMAIN'] = $arDomain['VALUE'];
                break;
            case $arParams['REGION_NAME_DECLINE_PP']:
                $arGeo['REPLACE_VALUES']['#REGION_NAME_DECLINE_PP#'] = $arDomain['VALUE'];
                break;
            case $arParams['REGION_NAME_DECLINE_IP']:
                $arGeo['REPLACE_VALUES']['#REGION_NAME_DECLINE_IP#'] = $arDomain['VALUE'];
                break;
            default:
                break;
        }
    }

    if ($arGeo['NAME'] == $arResult['geoIpData']['IP_CITY_NAME']){
        $arResult['IP_REGION'] = $arGeo['ID'];
    }
    if ($arGeo['DOMAIN'] == $_SERVER['SERVER_NAME']){
        $arResult['CURRENT_DOMAIN'] = $arGeo['ID'];
    }
    $arGeoList[$arGeo['ID']] = $arGeo;
}
$arResult['geoIBlock'] = $arGeoList;
$arResult['current_domain'] = $arResult['geoIBlock'][$arResult['CURRENT_DOMAIN']];
$arResult['ip_domain'] = $arResult['geoIBlock'][$arResult['IP_REGION']];
$arResult['chosen_domain']= $arResult['geoIBlock'][$_COOKIE['DOMAIN_ID']];

/*Устанавливаем глобальную переменную GEODJ*/
$_SESSION['GEODJ'] = $arResult['current_domain'];
/* Сразу редиректим на другой поддомен в зависимости от указанных параметров в query*/
$redirectQuery = $_GET[$arParams['REDIRECT_QUERY']];
if ($redirectQuery == 'ip' && $arResult['ip_domain']){
    LocalRedirect('https://'.$arGeoList[$arResult['IP_REGION']]['DOMAIN']);
} elseif ($redirectQuery == 'cookie' && $arResult['chosen_domain']){
    LocalRedirect('https://'.$arResult['chosen_domain']['DOMAIN']);
}
$this->IncludeComponentTemplate();