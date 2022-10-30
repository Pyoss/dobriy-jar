<?php

$curPage = $APPLICATION->GetCurPage(true);
$url_parts = explode('/', $curPage);

if ($url_parts[1] == 'bitrix' && $url_parts[2] == 'admin' && $url_parts[3] == 'iblock_element_edit.php' ) {
    $APPLICATION->AddHeadScript('/local/admin/custom.js');
}