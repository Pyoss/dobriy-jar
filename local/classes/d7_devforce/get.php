<?

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Application;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Service\GeoIp;
use Bitrix\Main\Web\Cookie;

Loader::includeModule("highloadblock");
Loader::includeModule("iblock");
Loader::includeModule("catalog");

class DJGet {

    // highload - Принимает в себя 2 параметра $id = id Таблицы
    // и $tuning = ассив с параметрами следующего вида:
    // array("select" => array("*"), "order" => array("ID" => "ASC"), "filter" => array("UF_PRODUCT_ID"=>"77","UF_TYPE"=>'33'))
    // https://devforces.ru/blog/api-d7-rabota-s-highload-blokam-v-bitriks/
    public function HighLoad($id, $config) {
        if (!$config['select']) {
            $config['select'] = array("*");
        }
        if (!$config['order']) {
            $config['order'] = array("ID" => "ASC");
        }
        $highload = HL\HighloadBlockTable::getById(intval($id))->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($highload);
        $entity_data_class = $entity->getDataClass();
        $rsData = $entity_data_class::getList($config);
        while ($arData = $rsData->Fetch()) if ($arData) $result[] = $arData;
        return $result;
    }

    // iblock - Принимает в себя 3 параметра $id = id Таблицы
    // и $config = массив с параметрами следующего вида:
    // $config = array(
    // "filter" => array("IBLOCK_ID"=>1, "ACTIVE"=>"Y"),
    // "select" => array("ID", "NAME", "CODE", "PREVIEW_TEXT"),
    // "order" => array("SORT" => "ASC"));
    // $props = true или false получать свойства как отдельный массив PROPERTIES или нет.
    // $price = true или false получать цену как отдельный массив PRICE или нет.
    // https://yournet.info/blog/bitrix/api-dlya-raboty-s-infoblokami-v-bitrix-d7
    public function IBlock($config, $props=false, $price=false) {
        $dbItems = \Bitrix\Iblock\ElementTable::getList($config);
        while ($dbItemsArray = $dbItems->fetch()) {
            if ($props) {
                $dbProperty = \CIBlockElement::getProperty($dbItemsArray['IBLOCK_ID'], $dbItemsArray['ID']);
                while ($arProperty = $dbProperty->Fetch()) $dbItemsArray['PROPERTIES'][] = $arProperty;
            }
            if ($dbItemsArray['ID'] and $price) {
                $arPrice = CCatalogProduct::GetOptimalPrice($dbItemsArray['ID'], 1, \Bitrix\Main\Engine\CurrentUser::get()->getUserGroups(), true);
                if (!$arPrice || count($arPrice) <= 0) {
                    if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($dbItemsArray['ID'], 1, \Bitrix\Main\Engine\CurrentUser::get()->getUserGroups())) {
                        $quantity = $nearestQuantity;
                        $arPrice = CCatalogProduct::GetOptimalPrice($dbItemsArray['ID'], 1, \Bitrix\Main\Engine\CurrentUser::get()->getUserGroups(), true);
                    }
                }
                $dbItemsArray['PRICE']["BASE_PRICE"] = $arPrice["RESULT_PRICE"]["BASE_PRICE"];
                $dbItemsArray['PRICE']["DISCOUNT_PRICE"] = $arPrice["RESULT_PRICE"]["DISCOUNT_PRICE"];
            }
            if ($dbItemsArray["IBLOCK_ID"] || $dbItemsArray["ID"]) $result[] = $dbItemsArray;
        }
        if ($result[0]["IBLOCK_ID"] || $result[0]["ID"]) return $result;
    }

    // UserList - Принимает в себя 1 параметр
    // $config = массив с параметрами следующего вида:
    // $config = array(
    // "filter" => array("ACTIVE"=>"Y"),
    // "select" => array("ID", "LOGIN", "PASSWORD", "EMAIL"),
    // "order" => array("ID" => "ASC"));
    // https://yournet.info/blog/bitrix/rabota-s-polzovatelyami-i-polzovatelskimi-polyami-v-bitrix-d7
    public function User($config) {
        $geUsertList = \Bitrix\Main\UserTable::getList($config);
        while ($user = $geUsertList->fetch()) if ($user["ID"]) $result[] = $user;
        if ($result[0]["ID"]) return $result;
    }

    // UserID Возвращает ID Текущего пользователя
    public function UserID()
    {
        return \Bitrix\Main\Engine\CurrentUser::get()->getId();
    }

    // UserEmail Возвращает Email Текущего пользователя
    public function UserEmail()
    {
        return \Bitrix\Main\Engine\CurrentUser::get()->getEmail();
    }

    // Login Возвращает Login Текущего пользователя
    public function UserLogin()
    {
        return \Bitrix\Main\Engine\CurrentUser::get()->getLogin();
    }

    // UserGroups Возвращает массив Groups с группами Текущего пользователя
    public function UserGroups()
    {
        return \Bitrix\Main\Engine\CurrentUser::get()->getUserGroups();
    }

    // UserFullName Возвращает Полное Имя Текущего пользователя
    public function UserFullName()
    {
        return \Bitrix\Main\Engine\CurrentUser::get()->getFullName();
    }

    // UserFirstName Возвращает Имя Текущего пользователя
    public function UserFirstName()
    {
        return \Bitrix\Main\Engine\CurrentUser::get()->getFirstName();
    }

    // UserLastName Возвращает Фамилию Текущего пользователя
    public function UserLastName()
    {
        return \Bitrix\Main\Engine\CurrentUser::get()->getLastName();
    }

    // UserSecondName Возвращает Отчество Текущего пользователя
    public function UserSecondName()
    {
        return \Bitrix\Main\Engine\CurrentUser::get()->getSecondName();
    }

    // isAdmin Проверяет есть ли у Текущего пользователя права администратора. Возвращает true или false
    public function isAdmin()
    {
        return \Bitrix\Main\Engine\CurrentUser::get()->isAdmin();
    }

    // server - Возвращает данные сервера, принемает в себя 1 параметр $code
    // server(false) = Возвращает массив $_SERVER
    // server("domain") = Возвращает текущий домен
    // server("url") = Возвращает текущий url
    // server("query") = Возвращает текущий строку GET запроса
    // server("ip") = Возвращает текущий IP адрес сервера
    // server("https") = Возвращает (true или false) проверяет включен ли протакол шыфрования SSL
    // server("page") = Возвращает символьный код текущей страницы
    public function server($code) {
        if (!$code) $result = $_SERVER;
        elseif ($code === "domain") $result = $_SERVER["SERVER_NAME"];
        elseif ($code === "url") $result = $_SERVER["REQUEST_URI"];
        elseif ($code === "query") $result = $_SERVER["QUERY_STRING"];
        elseif ($code === "ip") $result = $_SERVER["REMOTE_ADDR"];
        elseif ($code === "https") $result = $_SERVER["HTTP_HTTPS"];
        elseif ($code === "page") {
            $result = str_replace("/index.php", "", $_SERVER["PHP_SELF"]);
            $result = str_replace("/", "", $result);
        }
        return $result;
    }

}
