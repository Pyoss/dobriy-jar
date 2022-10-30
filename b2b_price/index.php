<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Выгрузка прайса");
if (!$USER -> isAdmin()){

    CHTTP::SetStatus("404 Not Found");
    @define("ERROR_404","Y");

    $rsSites = CSite::GetByID(SITE_ID);
    $arSite = $rsSites->Fetch();
    include($arSite['DOC_ROOT']."/404.php");
    return;
}

$APPLICATION->IncludeComponent('dj_components:dj.price_output', '', array());

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>