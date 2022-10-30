<?php

namespace DJScripts;

\CModule::IncludeModule('iblock');

class CatalogOutput
{
    private $productArray = [];

    private function productToArray($arRes)
    {
        if ($arRes['DETAIL_PICTURE']) {
            $arRes['DETAIL_PICTURE_URL'] = 'https://' . SITE_SERVER_NAME . \CFile::GetPath($arRes['DETAIL_PICTURE']);
        }
        $photoRes = \CIBlockElement::GetProperty(2, $arRes['ID'], array(), array('ID' => 13));
        while ($arPhoto = $photoRes->fetch()) {
            if (!$arPhoto['VALUE']){
                continue;
            }
            $arRes['MORE_PHOTO'][] = 'https://' . SITE_SERVER_NAME . \CFile::GetPath($arPhoto['VALUE']);
        }
        $articleRes = \CIBlockElement::GetProperty(2, $arRes['ID'], array(), array('CODE' => 'ARTNUMBER'));
        while ($arArticle = $articleRes->fetch()) {
            if (!$arArticle['VALUE']){
                continue;
            }
            $arRes['ARTNUMBER'] = $arArticle['VALUE'];
        }

        $arRes['SECTION_NAME'] = \CIBlockElement::GetElementGroups($arRes['ID'], false, array('NAME')) -> fetch()['NAME'];
        if (!$this->addProductOffers($arRes)) {

            $this->productArray[] = $arRes;
        }

    }

    private function offerToArray($arOffer, $arRes)
    {
        if ($arOffer['DETAIL_PICTURE']) {
            $arOffer['DETAIL_PICTURE_URL'] = 'https://' . SITE_SERVER_NAME . \CFile::GetPath($arOffer['DETAIL_PICTURE']);
        } else {
            $arOffer['DETAIL_PICTURE_URL'] = $arRes['DETAIL_PICTURE_URL'];
        }
        $arOffer['MORE_PHOTO'] = $arRes['MORE_PHOTO'];
        $arOffer['DETAIL_TEXT'] = $arRes['DETAIL_TEXT'];
        $arOffer['SECTION_NAME'] = $arRes['SECTION_NAME'];
        $arOffer['SEARCHABLE_CONTENT'] = $arRes['SEARCHABLE_CONTENT'];
        $photoRes = \CIBlockElement::GetProperty(3, $arOffer['ID'], array(), array('ID' => 24));
        while ($arPhoto = $photoRes->fetch()) {
            if (!$arPhoto['VALUE']){
                continue;
            }
            $arOffer['MORE_PHOTO'][] = 'https://' . SITE_SERVER_NAME . \CFile::GetPath($arPhoto['VALUE']);
        }
        $articleRes = \CIBlockElement::GetProperty(3, $arOffer['ID'], array(), array('CODE' => 'ARTNUMBER'));
        while ($arArticle = $articleRes->fetch()) {
            if (!$arArticle['VALUE']){
                continue;
            }
            $arOffer['ARTNUMBER'] = $arArticle['VALUE'];
        }
        $this->productArray[] = $arOffer;
    }

    public function getAllProducts()
    {
        $catalogRes = \CIBlockElement::GetList(array(), array('ACTIVE' => 'Y', 'IBLOCK_ID' => 2), false, array(),
            array('ID', 'NAME', 'DETAIL_PICTURE', 'DETAIL_TEXT', 'SEARCHABLE_CONTENT', 'CATALOG_PRICE_2'));
        while ($arRes = $catalogRes->fetch()) {
            $this->productToArray($arRes);
        }
    }

    private function addProductOffers($arRes)
    {
        $offersExists = false;
        foreach (\CCatalogSKU::getOffersList($arRes['ID'])[$arRes['ID']] as $offer) {
            $offersExists = true;
            $offerRes = \CIBlockElement::GetList(array(), array('ID' => $offer['ID'], 'ACTIVE' => 'Y', 'IBLOCK_ID' => $offer['IBLOCK_ID']), false, array('nTopCount' => 1),
                array('ID', 'NAME', 'DETAIL_PICTURE', 'CATALOG_PRICE_2'));
            while ($arOffer = $offerRes->fetch()) {
                $this->offerToArray($arOffer, $arRes);
            }
        }
        return $offersExists;
    }

    /**
     * @return array
     */
    public function getProductArray(): array
    {
        return $this->productArray;
    }
}