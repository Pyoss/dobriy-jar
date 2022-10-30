<?php


class DJgeo
{
    static function replaceText(&$string, $arKeyValue){
        foreach ($arKeyValue as $strVariable => $replaceValue){
            $string = str_replace($strVariable, $replaceValue, $string);
        }
    }

    static function geoReplace(&$string){
        $currentGeo = $_SESSION['GEODJ'];
        if (!$currentGeo){
            return;
        }
        DJgeo::replaceText($string, $currentGeo['REPLACE_VALUES']);
    }
}