<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?>
<div class="wrapper">
    <div class="slides" style="left:-<?php echo !$endless ? 0 : $VIEW_OFFSET?>px">
        <?php foreach($arResult['BANNER_DATA'] as $banner):
            preg_match("/[^\s]+/", $banner['NAME'], $first_word);
            preg_match("/(?> ).+/", $banner['NAME'], $second_word);
            if (!$second_word[0]){
                $second_word = $first_word;
                $first_word = false;
            } else {
                $first_word[0] .= '<br>';
            }
            $banner['PRINT_PRICE'] = number_format($banner['PRICE'], 0,
                    '', ' ') . ' &#8381;'
            ?>
            <div class="slide-wrapper" data-href="<?='/catalog/'.$banner['CODE'].'/'?>" style="width: <?php echo $VIEW_OFFSET?>px;">
                <div class="slide slide-popular_2">
                    <div class="slide-popular_2--name"><b><?=$banner['NAME']?></b></div>
                    <div class="slide-popular_2--bg" style="background-image: url('<?=$banner['PICTURE']?>');
                            height: <?php echo $height?>px"></div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
