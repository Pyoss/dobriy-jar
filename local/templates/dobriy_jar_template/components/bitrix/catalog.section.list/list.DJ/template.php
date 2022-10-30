<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="catalog-sections">
    <?foreach ($arResult['VIEW'] as $section):
        $sectionEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_EDIT');
        $sectionDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_DELETE');
        $sectionDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_SECTION_DELETE_CONFIRM'));
        $uniqueId = $section['ID'].'_'.md5($this->randString());
        $areaId = $this->GetEditAreaId($uniqueId);
        $this->AddEditAction($uniqueId, $section['EDIT_LINK'], $sectionEdit);
        $this->AddDeleteAction($uniqueId, $section['DELETE_LINK'], $sectionDelete, $sectionDeleteParams);

        preg_match("/[^\s]+/", $section['NAME'], $first_word);
        preg_match("/(?> ).+/", $section['NAME'], $second_word);
        ?>
    <div class="catalog-section" id="<?=$areaId?>">
        <a href="<?=$section['LINK']?>">
        </a>
        <div class="catalog-section--background">
        </div>
        <div class="catalog-section--wrapper">
            <div class="catalog-section--image-wrapper">
                <img class="load-transition" onload="this.style.opacity='1'" src="<?=$section['RESIZED_IMAGES'][$section['RESIZED_IMAGES']['optimal']]['path']?>">
            </div>
            <div class="catalog-section--upper-title"><?=$section['SUBTITLE']?></div>
            <div class="catalog-section--bold-title"><?='<b>' . $first_word[0] . '</b>' . $second_word[0]?></div>
        </div>
    </div>
    <?endforeach;?>
    <div class="catalog-section--background">
    </div>
</div>