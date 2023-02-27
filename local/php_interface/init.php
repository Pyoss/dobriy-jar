<?php
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();
$eventManager->unRegisterEventHandler("main", "OnAdminIBlockElementEdit", "seo", "\\Bitrix\\Seo\\AdvTabEngine", "eventHandler");

\Bitrix\Main\Loader::includeModule('sale');
\Bitrix\Main\Loader::includeModule('iblock');

function writeLog($data, $title = 'logs',$file = "/home/bitrix/www/local/app.log")
{
    $log = "\n------------------------\n";
    $log .= date("Y.m.d G:i:s") . "\n";
    $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
    $log .= print_r($data, 1);
    $log .= "\n------------------------\n";
    file_put_contents($file, $log, FILE_APPEND);
    return true;
}


$CLASSES_DIR = $_SERVER['DOCUMENT_ROOT'] . '/local/classes/';

// Тестируем необходимые классы (список не окончательный)
require_once $CLASSES_DIR . 'main.php';
require_once $CLASSES_DIR . 'integration_1C/Integration1C.php';
require_once $CLASSES_DIR . 'integration_1C/GUIDcontroller.php';
require_once $CLASSES_DIR . 'd7_devforce/get.php';
require_once $CLASSES_DIR . 'admin/Tab1C.php';
require_once $CLASSES_DIR . 'ajaxRes.php';
require_once $CLASSES_DIR . 'ajaxBasket.php';
require_once $CLASSES_DIR . 'geo.php';


AddEventHandler('main', 'OnAdminIBlockElementEdit', function () {
    $tabset = new Tab1C();
    return [
        'TABSET'  => 'some_tabset_name',
        'Check'   => [$tabset, 'check'],
        'Action'  => [$tabset, 'action'],
        'GetTabs' => [$tabset, 'getTabList'],
        'ShowTab' => [$tabset, 'showTabContent'],
    ];
});

// События которые срабатывают при создании или изменении элемента инфоблока
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "ResizeUploadedPhoto");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "ResizeUploadedPhoto");

// Кастомное свойство скидок
AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockBindDiscountProperty", "GetDescription"));
AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIBlockPackage", "GetDescription"));

// Убираем поиск по тексту при индексации
AddEventHandler("search", "BeforeIndex", "BeforeIndexHandler");

//Добавляем пункт в главное меню
AddEventHandler('main', 'OnBuildGlobalMenu', 'addMenuItem');

AddEventHandler("main", "OnBeforeUserLogin", Array("B2BUserDeny", "OnBeforeUserLoginHandler"));

class B2BUserDeny
{
    // создаем обработчик события "OnBeforeUserLogin"
    public static function OnBeforeUserLoginHandler(&$arFields)
    {
        $rsUser = CUser::GetByLogin($arFields["LOGIN"]);
        $arUser = $rsUser->Fetch();
        $arUserGroups = CUser::GetUserGroup($arUser['ID']);
        $b2bGroupId = 0;
        $result = \Bitrix\Main\GroupTable::getList(array(
            'select'  => array('ID'),
            'filter'  => array('STRING_ID'=>'b2b_clients')
        ));

        while ($arGroup = $result->fetch()) {
            $b2bGroupId = $arGroup['ID'];
        }
        if (in_array($b2bGroupId, $arUserGroups)) {
            global $APPLICATION;
            $APPLICATION->throwException("Вы пытаетесь авторизоваться на розничном сайте с аккаунтом B2B кабинета.\nДля доступа к B2B кабинету перейдите по ссылке https://b2b.dobriy-jar.ru, или авторизуйтесь с помощью аккаунта розничного сайта.");
            return false;
        }
        return true;
    }
}

/* Начисление бонусов */
$eventManager->AddEventHandler("main", "OnAfterUserRegister", "RegisterBonus");
$eventManager->AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");

$eventManager->AddEventHandler("sale", "OnSaleOrderSaved", "MyUpdateAccountBonus");

function MyUpdateAccountBonus(Bitrix\Main\Event $event)
{
    /** @var Bitrix\Sale\Order $order */
    $koef = 0.03; //Какой процент начислять
    $order = $event->getParameter("ENTITY");
    $oldValues = $event->getParameter("VALUES");
    $isNew = $event->getParameter("IS_NEW");
    if ($oldValues['STATUS_ID'] && $order->getField('STATUS_ID') !== $oldValues['STATUS_ID']) {
        if ($order->getField('STATUS_ID') == 'F') {
            $price = $order->getBasket()->getPrice();
            $order_id = $order->getId();
            $user_id = $order->getUserId();
            if ($price) {
                $sumBonus = round($price * $koef);
                $info = "Бонус за оплату заказа $order_id в размере $sumBonus";
                CSaleUserAccount::UpdateAccount($user_id, $sumBonus, 'RUB', $info, $order_id);
            }
        } else if ($oldValues['STATUS_ID'] == 'F' && $order->getField('STATUS_ID') == 'CL') {

            $price = $order->getBasket()->getPrice();
            $order_id = $order->getId();
            $user_id = $order->getUserId();
            $current_budget = CSaleUserAccount::GetByUserID($user_id, 'RUB')['CURRENT_BUDGET'];
            if ($price) {
                $sumBonus = round($price * $koef);
                $sumBonus = ($sumBonus > $current_budget) ? $current_budget : $sumBonus;
                $info = "Списание бонусов за отмену заказа $order_id в размере -$sumBonus";
                CSaleUserAccount::UpdateAccount($user_id, -$sumBonus, 'RUB', $info, $order_id);
            }
        }
    }
    /*
    $koef = 0.03; //Какой процент начислять
    $order = CSaleOrder::GetByID($order_id);
    if($status == "N"){
        $sumBonus = -($order['PRICE'] * $koef);
        $info = "Снятие денег со счета в связи с отменой оплаты в размере $order_id от заказа";
    }elseif($status == "Y"){
        $sumBonus = $order['PRICE'] * $koef;
        $info = "Бонус за оплату заказа в размере $order_id от заказа";
    }
    if ($order_id > 0 && $status == 'Y' && $order['CANCELED'] == "N") { // Заказ считается оплаченным
        CSaleUserAccount::UpdateAccount($order['USER_ID'], $sumBonus, 'EUR', $info, $order_id);
    }elseif ($order_id > 0 && $status == 'N'){
        CSaleUserAccount::UpdateAccount($order['USER_ID'], $sumBonus, 'EUR', $info, $order_id);
    }
    */
}
function OnAfterUserRegisterHandler($arFields)
{
    writeLog(print_r($arFields, true), 'ENTITY');
    $arEventFields = array(
        "LOGIN" => $arFields["LOGIN"],
        "PASSWORD" => $arFields["PASSWORD"],
        "EMAIL" => $arFields["EMAIL"],
        "CHECKWORD" => $arFields["CHECKWORD"],
        "SERVER_NAME" => "dobriy-jar.ru",
    );
    CEvent::Send("USER_INFO", SITE_ID, $arEventFields, "N", 2);
}

function RegisterBonus($arFields)
{
    if ($arFields['USER_ID']){
        if ($_POST['ref'] == 'lafetnikov'){
            CSaleUserAccount::UpdateAccount($arFields['USER_ID'], 500, 'RUB', 'Приветственный бонус Лафетникова');
        } else {
            CSaleUserAccount::UpdateAccount($arFields['USER_ID'], 100, 'RUB', 'Приветственный бонус');
        }
    }
}

function ResizeUploadedPhoto($arFields) {
    CModule::IncludeModule('iblock');
    $IBLOCK_IDs = array(2, 3); // ID инфоблока свойство которых нуждается в масштабировании
    $PROPERTY_CODE = "MORE_PHOTO";  // код свойства
    $imageMaxWidth = 800; // Максимальная ширина картинки
    $imageMaxHeight = 800; // Максимальная высота картинки
    // для начала убедимся, что изменяется элемент нужного нам инфоблока
    if(in_array($arFields["IBLOCK_ID"], $IBLOCK_IDs)) {
        $VALUES = $VALUES_OLD = array();
        //Получаем свойство значение сво-ва $PROPERTY_CODE
        $res = CIBlockElement::GetProperty($arFields["IBLOCK_ID"], $arFields["ID"], "sort", "asc", array("CODE" => $PROPERTY_CODE));
        while ($ob = $res->GetNext()) {
            $file_path = CFile::GetPath($ob['VALUE']); // Получаем путь к файлу
            if($file_path) {
                $imsize = getimagesize($_SERVER["DOCUMENT_ROOT"].$file_path); //Узнаём размер файла
                // Если размер больше установленного максимума
                if($imsize[0] > $imageMaxWidth or $imsize[1] > $imageMaxHeight) {
                    // Уменьшаем размер картинки
                    $file = CFile::ResizeImageGet($ob['VALUE'], array(
                        'width'=>$imageMaxWidth,
                        'height'=>$imageMaxHeight
                    ), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    // добавляем в массив VALUES новую уменьшенную картинку
                    $VALUES[] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$file["src"]);
                } else {
                    // добавляем в массив VALUES старую картинку
                    $VALUES[] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$file_path);
                }
                // Собираем в массив ID старых файлов для их удаления (чтобы не занимали место)
                $VALUES_OLD[] = $ob['VALUE'];
            }
        }
        // Если в массиве есть информация о новых файлах
        if(count($VALUES) > 0) {
            $PROPERTY_VALUE = $VALUES;  // значение свойства
            // Установим новое значение для данного свойства данного элемента
            CIBlockElement::SetPropertyValuesEx($arFields["ID"], $arFields["IBLOCK_ID"], array($PROPERTY_CODE => $PROPERTY_VALUE));
            // Удаляем старые большие изображения
            foreach ($VALUES_OLD as $key=>$val) {
                CFile::Delete($val);
            }
        }
        unset($VALUES);
        unset($VALUES_OLD);
    }
}

function BeforeIndexHandler($arFields) {
    $arrIblock = array(2, 3);
    $arDelFields = array("DETAIL_TEXT", "PREVIEW_TEXT") ;
    if (CModule::IncludeModule('iblock') && $arFields["MODULE_ID"] == 'iblock' && in_array($arFields["PARAM2"], $arrIblock) && intval($arFields["ITEM_ID"]) > 0){
        $dbElement = CIblockElement::GetByID($arFields["ITEM_ID"]) ;
        if ($arElement = $dbElement->Fetch()){
            foreach ($arDelFields as $value){
                if (isset ($arElement[$value]) && strlen($arElement[$value]) > 0){
                    $arFields["BODY"] = "";
                }
            }
            /*
            $db_props = CIBlockElement::GetProperty(                        // Запросим свойства индексируемого элемента
                $arFields["PARAM2"],         // BLOCK_ID индексируемого свойства
                $arFields["ITEM_ID"],          // ID индексируемого свойства
                array("sort" => "asc"),       // Сортировка (можно упустить)
                Array("CODE"=>"PRODUCT_TYPE"));
            if($ar_props = $db_props->Fetch()) {
                $arFields["TITLE"] = $ar_props['VALUE'] . ' ' . $arFields["TITLE"];
            }
            */
            $db_props = CIBlockElement::GetProperty(                        // Запросим свойства индексируемого элемента
                $arFields["PARAM2"],         // BLOCK_ID индексируемого свойства
                $arFields["ITEM_ID"],          // ID индексируемого свойства
                array("sort" => "asc"),       // Сортировка (можно упустить)
                Array("CODE"=>"ARTNUMBER"));
            if($ar_props = $db_props->Fetch()) {
                $arFields["BODY"] = $ar_props['VALUE'];
            }
        }
    }
    return $arFields;
}

class CIBlockBindDiscountProperty
{
    public function GetDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "N", #-----один из стандартных типов
            "USER_TYPE"            => "DISCOUNTCODE", #-----идентификатор типа свойства
            "DESCRIPTION"          => "Привязка к скидке (Добрый Жар Custom)",
            "GetPropertyFieldHtml" => array("CIBlockBindDiscountProperty", "GetPropertyFieldHtml"),
        );
    }

    /*--------- вывод поля свойства на странице редактирования ---------*/
    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $arProductDiscount = \Bitrix\Sale\Internals\DiscountTable::getList([
            'filter' => [
                'ID' => $value['VALUE'],
            ],
            'select' => [
                "*"
            ]
        ]) -> fetch();
        $description = count($arProductDiscount['NAME'])?'<b style="padding-left:10px">  '.$arProductDiscount['NAME'].'</b>':'  <b style="padding-left:10px;color:red">Скидка не найдена</b>';
        $info = \Bitrix\Sale\Internals\DiscountEntitiesTable::getMap([$value['VALUE']]) ;
        print_r('<pre>' . print_r($info, true) . '</pre>');
        return '<input type="text" name="'.$strHTMLControlName["VALUE"].'" value="'.$value['VALUE'].'">' . $description;
    }
}

$GLOBALS['COMP_COUNT'] = 1;


class CIBlockPackage
{
    public function GetDescription()
    {
        return array(
            "PROPERTY_TYPE"        => "N", #-----один из стандартных типов
            "USER_TYPE"            => "PACKAGE", #-----идентификатор типа свойства
            "DESCRIPTION"          => "Комплектация",
            "GetPropertyFieldHtml" => array("CIBlockPackage", "GetPropertyFieldHtml"),
            "ConvertToDB" => array(__CLASS__, "ConvertToDB"), #-----функция конвертирования данных перед сохранением в базу данных
        );
    }

    public function ConvertToDB($arProperty, $arValue){
        if (strlen($arValue['VALUE'])) {
            $item = json_decode($arValue['VALUE'], true)['item'];
            if (ctype_digit($item)){
                return $arValue;
            }
        }
        return null;
    }

    /*--------- вывод поля свойства на странице редактирования ---------*/
    public function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $value_array = json_decode($value['VALUE'], true);
        $pr = print_r($value_array['item'], true);
        if(ctype_digit($value_array['item'])){
            $element_data = \Bitrix\Iblock\ElementTable::getById($value_array['item']) -> fetch();
        }
        $input_string = <<<NOWDOC
        <span style="display:inline-block;padding: 2px 0 10px 0">${element_data['NAME']} ${value_array['quantity']}</span><br>
        <template data-row="${GLOBALS['COMP_COUNT']}"></template>
        <input type="text" name=COMP[n${GLOBALS['COMP_COUNT']}] id=COMP[n${GLOBALS['COMP_COUNT']}] value="${value_array['item']}" size="5" type="text">
        <input type="button" value="..." onclick="jsUtils.OpenWindow('/bitrix/admin/iblock_element_search.php?lang=ru&amp;IBLOCK_ID=2&amp;n=COMP&amp;k=n${GLOBALS['COMP_COUNT']}&amp;iblockfix=y&amp;tableId=iblockprop-E-${arProperty['ID']}-2', 900, 700);">
        <input type="text" placeholder="Комментарий" name=COMMENT[n${GLOBALS['COMP_COUNT']}] id=COMMENT[n${GLOBALS['COMP_COUNT']}] value="${value_array['comment']}" size="80">
        <input type="text" placeholder="Раздел" name=GROUP[n${GLOBALS['COMP_COUNT']}] id=GROUP[n${GLOBALS['COMP_COUNT']}] value="${value_array['group']}" size="30" type="text">
        <br>
        <input type="text" style="display:none" name=PROP[${arProperty['ID']}][n${GLOBALS['COMP_COUNT']}] id=PROP[${arProperty['ID']}][n${GLOBALS['COMP_COUNT']}] value='${value['VALUE']}' size="20" type="text">
        
        
        NOWDOC;

        $GLOBALS['COMP_COUNT'] += 1;
        return $input_string;
    }
}

function addMenuItem(&$aGlobalMenu, &$aModuleMenu)
{
    global $USER;

    if ($USER->IsAdmin()) {

        $aGlobalMenu['global_menu_custom'] = [
            'menu_id' => 'integration1C_global_menu',
            'text' => 'Интеграция 1С',
            'title' => 'Интерарция 1С',
            'url' => 'settingss.php?lang=ru',
            'sort' => 1000,
            'items_id' => 'integration1C_global_menu',
            'help_section' => 'custom',
            'items' => [
                [
                    'parent_menu' => 'integration1C_global_menu',
                    'sort'        => 10,
                    'url'         => 'integration1C.php?lang=ru',
                    'text'        => 'Интеграция 1С',
                    'title'       => 'Интеграция 1С',
                    'icon'        => 'fav_menu_icon',
                    'page_icon'   => 'fav_menu_icon',
                    'items_id'    => '1C_integration_menu',
                ]
            ],
        ];

    }
}
