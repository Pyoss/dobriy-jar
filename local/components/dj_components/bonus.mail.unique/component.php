<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
use Bitrix\Iblock;
use Bitrix\Main;
use Bitrix\Main\Loader;
//$this->includeComponentTemplate();

$secret='-bonus' . $arParams['BONUS_AMOUNT'];
// String of all alphanumeric character
$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
$length_of_string = 10;
$new_checkword = substr(str_shuffle($str_result),0, $length_of_string) . $secret;
$current_user = new CUser;
$fields = ['UF_SPECIAL_WORD' => $new_checkword];
$current_user -> Update($arParams['USER_ID'], $fields);
$link = "https://www.dobriy-jar.ru/personal/ref/" . $new_checkword . '/';

?>
<div>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockButton_mr_css_attr" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;border-collapse: collapse;table-layout: fixed;">
        <tbody class="bxBlockOut_mr_css_attr">
        <tr>
            <td valign="top" class="bxBlockPadding_mr_css_attr bxBlockInn_mr_css_attr bxBlockInnButton_mr_css_attr" style="padding-top: 9px;padding-right: 18px;padding-bottom: 9px;padding-left: 18px;padding: 9px 18px 9px 18px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" class="bxBlockContentButtonEdge_mr_css_attr" style="background-color: #ff7300;border-radius: 3px;text-align: center;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;border-collapse: collapse;table-layout: fixed;">
                    <tbody>
                    <tr>
                        <td valign="top" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" class="MsoNormal_mr_css_attr">
                            <a class="bxBlockContentButton_mr_css_attr" title="Получить <?=$arParams['BONUS_AMOUNT']?> бонусов" href="<?=$link?>" target="_blank" style="display: inline-block;line-height: 22px;color: #fff;padding: 7px 15px;text-decoration: none;text-align: center;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-wrap: break-word;font-size: 24px" rel=" noopener noreferrer">
                                Получить <?=$arParams['BONUS_AMOUNT']?> бонусов
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
