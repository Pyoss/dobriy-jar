<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arParams['SUBSECTION_MAX_VIEW'] = 4;
$arParams['COLUMN_NUMBER'] = 4;
$currentDepthLevel = 1;
$sections = array();
$arLength = count($arResult);
$iter = -1;
while($iter + 1 < count($arResult)){
    $res = iterateSection($iter, $arResult);
    $iter = $res['iterator'];
    $section = $res['section'];
    $sections[] = $section;
}

function iterateSection($iterator, $result){
    $iterator ++;
    $section = $result[$iterator];

    $entity = \Bitrix\Iblock\Model\Section::compileEntityByIblock(2);
    if ($section['PARAMS']['ID']){
        $rs = $entity::getList(
            ['select' => ['UF_IBLOCK_SECTION_NAME', 'UF_SECTION_MENU_LINK', 'UF_SECTION_LINK_PICTURE'],
             'filter' => ['ID' => $section['PARAMS']['ID']]]);
        while ($ar = $rs -> Fetch()){
            for ($i=0; $i < count($ar['UF_IBLOCK_SECTION_NAME']) && $ar['UF_IBLOCK_SECTION_NAME']; $i++){
                $section['MENU_LINKS'][$ar['UF_IBLOCK_SECTION_NAME'][$i]] =
                    ['MENU_LINK' => $ar['UF_SECTION_MENU_LINK'][$i],
                        'MENU_PICTURE' => $ar['UF_SECTION_LINK_PICTURE'][$i]];
            }
        }
    }
    if ($section['MENU_LINKS']){
        foreach($section['MENU_LINKS'] as $text => $data_array) {
            $section['CHILDREN'][] = ['TEXT' => $text, 'LINK'=>$data_array['MENU_LINK'],
                'PARAMS' => ['DETAIL_PICTURE' => CFile::GetPath($data_array['MENU_PICTURE'])]];
        }
    }

    if ($section['IS_PARENT']){
        while($result[$iterator + 1]['DEPTH_LEVEL'] > $section['DEPTH_LEVEL']){
            $res = iterateSection($iterator, $result);
            $iterator = $res['iterator'];
            $section['CHILDREN'][] = $res['section'];
        }
    }
    $res = ['iterator' => $iterator,
        'section' => $section];
    return $res;
}

$arResult['SECTIONS'] = $sections;
$arResult['TEST_PRODUCT'] = [
    'IMAGE' => DJMain::IMAGE_TEMPLATE_SRC,
    'PRODUCT_TYPE' => 'Самогонный аппарат',
    'PRODUCT_NAME' => 'Феникс Мечта'];