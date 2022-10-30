<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<div class="wrapper">
    <div class="slides" style="left:-<?php echo !$endless ? 0 : $VIEW_OFFSET?>px">
        <?php foreach($arResult['BANNER_DATA'] as $banner):?>
            <div class="slide-wrapper" style="width: <?php echo $VIEW_OFFSET?>px;
                    height: <?php echo $height?>px; background-image: url('<?=$banner['IMG']?>')">
            </div>
        <?php endforeach;?>
    </div>
</div>
