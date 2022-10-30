<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (empty($arResult["CATEGORIES"]) || !$arResult['CATEGORIES_ITEMS_EXISTS'])
	return;

function sortSections($a, $b): int
{
    $SECT = [];
    foreach([$a, $b] as $item){
        if(strpos($item['ITEM_ID'], 'S') !== false){
            $SECT[] = $item;
        }
    }
    if(in_array($a, $SECT) and !in_array($b, $SECT)){
        return -1;
    } else if (!in_array($a, $SECT) and in_array($b, $SECT)){
        return 1;
    }
    return 0;
}
?>
<div class="search-title">
<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):
    usort($arCategory["ITEMS"], 'sortSections');
    ?>
	<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
        <a class="search-title-result--link" href="<?echo $arItem["URL"]?>">
            <?if($category_id === "all"):?>
                <div class="search-title-result--all">
                    Показать все результаты
                </div>
            <?elseif(isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):
                $arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];?>
                <div class="search-title-result--item">

                    <?if (is_array($arElement["PICTURE"])):?>
                        <div class="search-title-result--image-container"
                             style="
                                     background-image: url('<?echo $arElement["PICTURE"]["src"]?>');
                                     background-size: contain;
                                     background-position: center;
                                     background-repeat: no-repeat;
                                     ">
                        </div>
                    <?endif;?>
                    <div class="search-title-result--info">
                        <span class="search-title--name"><?echo $arItem["NAME"]?></span>
                        <?
                        foreach($arElement["PRICES"] as $code=>$arPrice)
                        {
                            if ($arPrice["MIN_PRICE"] != "Y")
                                continue;

                            if($arPrice["CAN_ACCESS"])
                            {
                                if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
                                    <div class="search-title-result--item-price">
                                        <span class="search-title-result--item-current-price">от <?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span>
                                        <span class="search-title-result--item-old-price"><?=$arPrice["PRINT_VALUE"]?></span>
                                    </div>
                                <?else:?>
                                    <div class="search-title-result--item-price">
                                        <span class="search-title-result--item-current-price">от <?=$arPrice["PRINT_VALUE"]?></span>
                                    </div>
                                <?endif;
                            }
                            if ($arPrice["MIN_PRICE"] == "Y")
                                break;
                        }
                        ?>
                    </div>
                </div>
            <?elseif(isset($arResult["FOUND_CATEGORIES"][$arItem["ITEM_ID"]])):?>
            <div class="search-title-result--category">
                <div class="search-title-result--info">
                    <span class="search-title--name"><?echo $arItem["NAME"]?></span>
                    <br>
                    <span class="search-title--sub">
                    <? foreach($arResult['FOUND_CATEGORIES'][$arItem["ITEM_ID"]]['NAME_PATH'] as $category)
                        {
                        echo ' / '.$category;
                        }
                        ?>
                    </span>
                </div>
                <div class="search-title-result--image-container"
                     style="
                             background-image: url('<?echo $arResult['FOUND_CATEGORIES'][$arItem["ITEM_ID"]]["PICTURE"]["src"]?>');
                             background-size: contain;
                             background-position: center;
                             background-repeat: no-repeat;
                             ">
                </div>
            </div>
            <?endif;?>
        </a>
	<?endforeach;?>
<?endforeach;?>
</div>