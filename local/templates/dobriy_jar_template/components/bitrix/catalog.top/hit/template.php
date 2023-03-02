<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogTopComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);
?>

    <?php
    if (!empty($arResult['ITEMS'])):
        foreach ($arResult['ITEMS'] as $item):
            $APPLICATION->IncludeComponent(
                'bitrix:catalog.item',
                'hit',
                array(
                    'RESULT' => array(
                        'ITEM' => $item,
                    ),
                    'PARAMS' => $arParams
                        + array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
                ),
                $component,
                array('HIDE_ICONS' => 'Y')
            );
        endforeach;
    endif;
    ?>