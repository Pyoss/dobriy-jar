<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

$this->setFrameMode(true);

if (!$arResult["NavShowAlways"]) {
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}

?>

<div class="bx-pagination">
    <div class="bx-pagination-container">
        <ul>
            <!--<li class="bx-pag-prev<?=$arResult["NavPageNomer"] == 1 ? ' current noselect': ' active'?>"><span data-page-nav="prev"><? echo GetMessage("round_nav_back") ?></span>
            </li>--!>
                <li>Страницы:</li>
            <?for ($i = 1; $i <= $arResult["NavPageCount"]; $i++):?>
                <li class="bx-pag-page<?=$arResult["NavPageNomer"] == $i ? ' current noselect': ' active'?>"><span data-page-nav="<?=$i?>"><?=$i?></span>
                </li>

            <?endfor;?>
            <!--<li class="bx-pag-next<?=$arResult["NavPageNomer"] == $arResult["NavPageCount"] ? ' current noselect': ' active'?>"><span data-page-nav="next"><? echo GetMessage("round_nav_forward") ?></span></li>--!>
        </ul>
        <div style="clear:both"></div>
    </div>
</div>
