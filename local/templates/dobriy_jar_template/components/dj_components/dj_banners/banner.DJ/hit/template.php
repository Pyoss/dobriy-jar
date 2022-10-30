<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<div class="wrapper">
    <div class="slides" style="left:-<?php echo !$endless ? 0 : $VIEW_OFFSET?>px">
        <?php foreach($arResult['BANNER_DATA'] as $banner):
            $banner['PRINT_PRICE'] = number_format($banner['PRICE'], 0,
                    '', ' ') . ' &#8381;'
            ?>
            <div class="slide-wrapper" style="width: <?php echo $VIEW_OFFSET?>px;" data-href="<?=$banner['CODE']?>">
                <div class="slide">
                    <div class="slide-hit--bg" style="background-image: url('<?=$banner['PICTURE']?>');
                            height: <?php echo $height?>px"></div>
                    <div class="slide-hit--name"><?=$banner['TYPE']?><br><b><?=$banner['VIEW_NAME']?></b></div>
                    <?if($banner['PRICE'] != '0.00'):?>
                    <div class="slide-hit--price"><span><?=$banner['PRINT_PRICE']?></span></div>
                    <?endif;?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
