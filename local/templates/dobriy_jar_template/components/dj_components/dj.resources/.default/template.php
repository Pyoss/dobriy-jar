<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();}
?>
 <section class="useful">
     <div class="useful-wrapper">
<?
foreach ($arResult['SECTIONS'] as $section):?>
<a href="<?=$section["LINK"]?>">
<div class="useful-section--wrapper" style="background-image: url(<?=$section["BACKGROUND"]?>)">
    <div class="useful-section--container">
        <div class="useful-section--name"><?=$section["NAME"]?></div>
        <div class="useful-section--text"><?=$section["TEXT"]?></div>
    </div>
</div>
     </a>
<?php endforeach?>
     </div>
 </section>