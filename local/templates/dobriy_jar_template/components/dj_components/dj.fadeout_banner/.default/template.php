<?php

CModule::IncludeModule('dj.imgref');

use DJScripts\ImgRef;

$resizedData = ImgRef::optimizeImg(952, array('width' => 250, 'height' => 250), false);
?>

    <img src="<?= $resizedData['webp']['path'] ?>">
    <img src="<?= $resizedData['default']['path'] ?>">

<?php
