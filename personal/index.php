<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");

function updateSpecialCheckword($user_id, $secret=''){
    // String of all alphanumeric character
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $length_of_string = 10;
    $new_checkword = substr(str_shuffle($str_result),0, $length_of_string) . $secret;
    $current_user = new CUser;
    $fields = ['UF_SPECIAL_WORD' => $new_checkword];
    $current_user -> Update($user_id, $fields);
    return $new_checkword;
}

$arUri = explode('/', $_SERVER['REQUEST_URI']);
$refIndex =  array_search('ref', $arUri);
if ($refIndex){
    $bonusCheckword = $arUri[$refIndex + 1];
    $usr = new CUser;
    $order = array('sort' => 'asc');
    $tmp = 'sort';
    $filter = array('UF_SPECIAL_WORD' => $bonusCheckword);
    $resUser = $usr -> GetList($order, $tmp, $filter);
    $USER_TO_LOG = false;
    while($arUser = $resUser -> fetch()){
        $USER_TO_LOG = $arUser['ID'];
    }
    if ($USER_TO_LOG){
        if (strpos($bonusCheckword, 'bonus') !== false){
            $bonusString = explode('-', $bonusCheckword)[1];
            $bonusAmount = substr($bonusString, 5);
            CSaleUserAccount::UpdateAccount($USER_TO_LOG, (int)$bonusAmount, 'RUB', 'Бонус по коду ' . $bonusString);
            echo '<div class="center-content" style="color: limegreen; font-weight: bold">На ваш личный счет записано ' . $bonusAmount . ' бонусных баллов! <br><br>Заполните личные данные и установите пароль.</div>';
        }
        updateSpecialCheckword($USER_TO_LOG);
        $USER -> Authorize($USER_TO_LOG);
    }

}
$user = $USER->GetID();
if (!$user){
    LocalRedirect('/auth/');
}



$APPLICATION->IncludeComponent(
    "bitrix:breadcrumb",
    ".default",
    array(
        "START_FROM" => "1",
        "PATH" => "",
        "SITE_ID" => "s1",
        "COMPONENT_TEMPLATE" => ".default"
    ),
    false
);
?>

    <div class="center-content">
        <?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.section", 
	"dj.personal", 
	array(
		"ACCOUNT_PAYMENT_ELIMINATED_PAY_SYSTEMS" => array(
			0 => "0",
		),
		"ACCOUNT_PAYMENT_PERSON_TYPE" => "1",
		"ACCOUNT_PAYMENT_SELL_SHOW_FIXED_VALUES" => "Y",
		"ACCOUNT_PAYMENT_SELL_TOTAL" => array(
			0 => "100",
			1 => "200",
			2 => "500",
			3 => "1000",
			4 => "5000",
			5 => "",
		),
		"ACCOUNT_PAYMENT_SELL_USER_INPUT" => "Y",
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHECK_RIGHTS_PRIVATE" => "N",
		"COMPATIBLE_LOCATION_MODE_PROFILE" => "N",
		"CUSTOM_PAGES" => "",
		"CUSTOM_SELECT_PROPS" => array(
		),
		"NAV_TEMPLATE" => "",
		"ORDER_HISTORIC_STATUSES" => array(
			0 => "F",
		),
		"PATH_TO_BASKET" => "/personal/order/make",
		"PATH_TO_CATALOG" => "/catalog/",
		"PATH_TO_CONTACT" => "/about/contacts",
		"PATH_TO_PAYMENT" => "/personal/order/payment/",
		"PER_PAGE" => "20",
		"PROP_1" => array(
		),
		"PROP_2" => array(
		),
		"SAVE_IN_SESSION" => "Y",
		"SEF_FOLDER" => "/personal/",
		"SEF_MODE" => "Y",
		"SEND_INFO_PRIVATE" => "N",
		"SET_TITLE" => "Y",
		"SHOW_ACCOUNT_COMPONENT" => "Y",
		"SHOW_ACCOUNT_PAGE" => "N",
		"SHOW_ACCOUNT_PAY_COMPONENT" => "Y",
		"SHOW_BASKET_PAGE" => "Y",
		"SHOW_CONTACT_PAGE" => "N",
		"SHOW_ORDER_PAGE" => "Y",
		"SHOW_PRIVATE_PAGE" => "Y",
		"SHOW_PROFILE_PAGE" => "N",
		"ALLOW_INNER" => "N",
		"ONLY_INNER_FULL" => "N",
		"SHOW_SUBSCRIBE_PAGE" => "N",
		"USER_PROPERTY_PRIVATE" => "",
		"USE_AJAX_LOCATIONS_PROFILE" => "N",
		"COMPONENT_TEMPLATE" => "dj.personal",
		"ACCOUNT_PAYMENT_SELL_CURRENCY" => "RUB",
		"ORDER_HIDE_USER_INFO" => array(
			0 => "0",
		),
		"ORDER_RESTRICT_CHANGE_PAYSYSTEM" => array(
			0 => "0",
		),
		"ORDER_DEFAULT_SORT" => "STATUS",
		"ORDER_REFRESH_PRICES" => "N",
		"ORDER_DISALLOW_CANCEL" => "N",
		"ORDERS_PER_PAGE" => "20",
		"PROFILES_PER_PAGE" => "20",
		"MAIN_CHAIN_NAME" => "Мой кабинет",
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"orders" => "orders/",
			"account" => "account/",
			"subscribe" => "subscribe/",
			"profile" => "profiles/",
			"profile_detail" => "profiles/#ID#",
			"private" => "private/",
			"order_detail" => "orders/#ID#",
			"order_cancel" => "cancel/#ID#",
		)
	),
	false
);?></div><br>
    <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>