<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */
global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
    return "";
$strReturn = '';
//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()
$strReturn .= '<div class="bx-breadcrumb" id="bx-breadcrumb" itemprop="http://schema.org/breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';

$itemSize = count($arResult);
for($index = $itemSize-2; $index < $itemSize; $index++)
{
    $title = htmlspecialcharsex($arResult[$index]['TITLE']);
    DJgeo::geoReplace($title);
    $arrow = ($index == $itemSize-1 || !$arResult[$index]["LINK"]? '' : '/ ' );

    $strReturn .= '
        <div class="bx-breadcrumb-item" id="bx_breadcrumb_'.$index.'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            
            <a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" itemprop="item" class="dj_link">
                <'.(($index !== $itemSize-1) ? 'span' : 'h1') . ' itemprop="name">'.$title.'</'.(($index !== $itemSize-1) ? 'span' : 'h1') . '>
            </a>
            <meta itemprop="position" content="'.($index + 1).'" />
            <span class="divider">'.$arrow.'</span>
        </div>';
}

$strReturn .= '</div>';

return $strReturn;
