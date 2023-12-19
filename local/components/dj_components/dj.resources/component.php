<?php


if ($this->StartResultCache()) {
    for ($i = 1; $i <= $arParams['SECTION_NUMBER']; $i++) {
        $arResult["SECTIONS"][$i]["NAME"] = $arParams["SECTION_NAME_" . $i];
        $arResult["SECTIONS"][$i]["TEXT"] = $arParams["SECTION_TEXT_" . $i];
        $arResult["SECTIONS"][$i]["BACKGROUND"] = $arParams["SECTION_BACKGROUND_" . $i];
        $arResult["SECTIONS"][$i]["LINK"] = $arParams["SECTION_LINK_" . $i];
    }
    $this->IncludeComponentTemplate();
}