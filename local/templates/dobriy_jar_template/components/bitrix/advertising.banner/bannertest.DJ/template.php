<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$TEST_PARAMETERS = array();
$TEST_PARAMETERS['WIDTH'] = '400px';
$TEST_PARAMETERS['HEIGHT'] = '300px';
$TEST_PARAMETERS['SLIDES_VIEW'] = 2;
$TEST_PARAMETERS['ENDLESS'] = false;
$VIEW_OFFSET = (int)($TEST_PARAMETERS['WIDTH']/$TEST_PARAMETERS['SLIDES_VIEW']);


?>
<div id="slider" class="slider"
     style="width:<?php echo $TEST_PARAMETERS['WIDTH']?>" data-slides="<?php echo $TEST_PARAMETERS['SLIDES_VIEW']?>"
     data-endless="<?php echo (int)$TEST_PARAMETERS['ENDLESS']?>">
    <div class="wrapper">
        <div id="slides" class="slides" style="left:-<?php echo !$TEST_PARAMETERS['ENDLESS'] ? 0 : $VIEW_OFFSET?>px">
            <span class="slide" style="width: <?php echo $VIEW_OFFSET?>px;
                        height: <?php echo $TEST_PARAMETERS['HEIGHT']?>">Slide 1</span>
            <span class="slide" style="width: <?php echo $VIEW_OFFSET?>px;
                        height: <?php echo $TEST_PARAMETERS['HEIGHT']?>">Slide 2</span>
            <span class="slide" style="width: <?php echo $VIEW_OFFSET?>px;
                        height: <?php echo $TEST_PARAMETERS['HEIGHT']?>">Slide 3</span>
            <span class="slide" style="width: <?php echo $VIEW_OFFSET?>px;
                        height: <?php echo $TEST_PARAMETERS['HEIGHT']?>">Slide 4</span>
            <span class="slide" style="width: <?php echo $VIEW_OFFSET?>px;
                        height: <?php echo $TEST_PARAMETERS['HEIGHT']?>">Slide 5</span>
        </div>
    </div>
    <a id="prev" class="control prev"></a>
    <a id="next" class="control next"></a>
</div>
<?php

?>