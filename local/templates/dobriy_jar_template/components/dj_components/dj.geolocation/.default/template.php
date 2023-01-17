<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){die();}
$current_domain = $arResult['current_domain'];
$ip_domain = $arResult['ip_domain'];
$chosen_domain = $arResult['chosen_domain'];
?>
<div class="geo noselect" id="geo-wrapper">
    <div class="click-wrapper" id="geo">
    <i class="inline-icon geo-icon"></i>
        <span><?=$arResult['geoIBlock'][$arResult['CURRENT_DOMAIN']]['NAME']?></span>
    </div>
</div>
<script>
    let current_domain = new GeoData('<?=$current_domain['DOMAIN']?>', '<?=$current_domain['NAME']?>', '<?=$current_domain['ID']?>')
    let ip_domain = new GeoData('<?=$ip_domain['DOMAIN']?>', '<?=$ip_domain['NAME']?>', '<?=$ip_domain['ID']?>')
    let chosen_domain = new GeoData('<?=$chosen_domain['DOMAIN']?>', '<?=$chosen_domain['NAME']?>', '<?=$chosen_domain['ID']?>')
    let domains = <?=CUtil::PhpToJSObject($arResult['geoIBlock'])?>;
    var domain_array = Object.keys(domains).sort(function(a, b) {
        return parseInt(domains[a].SORT) - parseInt(domains[b].SORT)}
        ).map(function(key) {
            return new GeoData(domains[key]['DOMAIN'], domains[key]['NAME'], domains[key]['ID']);
        });
</script>