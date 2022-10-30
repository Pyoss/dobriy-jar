<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("");?>

<?
$APPLICATION->IncludeComponent(
    "dj_components:dj.mpstat",
    ""
)
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
