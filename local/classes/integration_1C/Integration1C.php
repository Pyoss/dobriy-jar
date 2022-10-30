<?php


class Integration1C
{
    //-----------------------------------------------------
    // Инициируем синглтон с файлами конфигурации
    private static $instances = [];
    public $handlers = array();
    protected function __clone() { }
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
    public static function getInstance(): Integration1C
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    private static function GetConfig()
    {
        $filename = dirname(__FILE__) . '/.conf.txt';
        return include $filename;
    }

    private static function RewriteConfig(array $config)
    {
        $filename = dirname(__FILE__) . '/.conf.txt';
        $config = var_export($config, true);
        $content = "<?php return $config ;";
        file_put_contents($filename, $content);
    }

    public function start_log(){

        $fh = fopen( dirname(__FILE__) . '/log.txt',  'w' );
        fclose($fh);

    }

    public function log($line){
        $fp = fopen(dirname(__FILE__) . '/log.txt', 'a');
        fwrite($fp, $line);
        fclose($fp);
    }

    // Сам запрос Curl
    private function CurlGet($link)
    {
        $username = 'UsrSite';
        $password = "UsrSite%705";
        $credentials = base64_encode("$username:$password");
        $headers = [];
        $headers[] = "Authorization: Basic {$credentials}";
        $curlDeal = curl_init();
        curl_setopt_array($curlDeal, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $link));

        curl_setopt($curlDeal, CURLOPT_HTTPHEADER, $headers);
        return curl_exec($curlDeal);
    }

    // Получение полного списка номернклатуры из 1С
    public function GetCatalog(){
        $link = 'https://1c.dobriy-jar.ru/workbase/hs/SiteApi/catalog';
        return json_decode($this -> CurlGet($link), true)['products'];
    }

    public function fillCodes(){
        $guid_controller = new GUIDController();
        $resGuid = $guid_controller -> getAllGuidsRes();
    }

    /***
     * Регистрирует или обновляет текущую цену каталога.
     * @param $productId
     * @param $priceTable - массив вида ["CATALOG_GROUP_ID" => "PRICE"]
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    function registerPrices($productId, $priceTable){
        $productPricesRes = \Bitrix\Catalog\PriceTable::getList([
            "select" => ["*"],
            "filter" => [
                "=PRODUCT_ID" => $productId,
            ],
            "order" => ["CATALOG_GROUP_ID" => "ASC"]
        ]);

        foreach ($priceTable as $PRICE_GROUP_ID => $PRICE_VALUE){

            $priceDataArray = array(
                "CATALOG_GROUP_ID" => $PRICE_GROUP_ID,
                "PRICE" => $PRICE_VALUE,
                "PRICE_SCALE" => $PRICE_VALUE,
                "CURRENCY" => "RUB",
                "PRODUCT_ID" => $productId
            );
            while ($row = $productPricesRes -> Fetch()){

                // ФОРМИРУЕМ МАССИВ СО ВСЕМИ ДАННЫМИ О ЦЕНОВОМ ПРЕДЛОЖЕНИИ

                if ($row["CATALOG_GROUP_ID"] == $PRICE_GROUP_ID){
                    \Bitrix\Catalog\PriceTable::update($row['ID'], $priceDataArray);
                    // НАШЛИ ЗАПИСЬ, ОБНОВИЛИ И ПРЕРЫВАЕМ ЦИКЛ foreach;
                    continue 2;
                }
            }
            // ЕСЛИ ЦИКЛ НЕ ПРЕРВАН СОЗДАЕМ НОВУЮ ЗАПИСЬ В ТАБЛИЦЕ ЦЕН
            \Bitrix\Catalog\PriceTable::add($priceDataArray);
        }
    }

    function updateQuantity($productId, $quantity){
        CCatalogProduct::Update($productId, array('QUANTITY' => $quantity));
    }

    /***
     * @param $arGuid - строка записи в таблице GUID_1С
     * @param $product - элемент полученного из GetCatalog() массива
     */
    public function updateCodes($arGuidId, $product){
        $el = new CIBlockElement;
        $el -> update($arGuidId,
            array("CODE" => CUtil::translit($product['name'], 'ru'),
                "PROPERTY_VALUES"=> array(
                    "ARTNUMBER" => $product['article']
                ),));
    }

    public function UpdateByGuids(){
        $this -> start_log();
        //Полный список, полученный от базы 1С
        $catalog = $this -> GetCatalog();
        //Полный список, полученный от GUID
        $guid_controller = new GUIDController();
        $counter = 0;

        //$this->log(print_r($catalog, true));
        foreach($catalog as $product_id => $product){

            /*
            while ($offset > 0){
                $offset --;
                continue 2;
            }
            $counter ++;
            if ($counter > $limit){
                break;
            }
            */

            //устанавливаем типы цен
            $priceArray = array(
                2 => $product['retail_price'],
                3 => $product['trade_price']
            );
            $product['available'] = $product['fix_available'] == 1 ? 100 : $product['available'];

            $arGuid = $guid_controller -> getRowByGUID($product['GUID']);
            if ($arGuid) {
                $updated = $guid_controller -> updateRow($product, $arGuid);
                $this->registerPrices($arGuid['UF_ELEMENT_ID'], $priceArray);
                $this->updateQuantity($arGuid['UF_ELEMENT_ID'], $product['available']);
            }
            /* else {
                $this->createProduct($product['GUID'], $product['name'],
                    $product['article'], $priceArray, ($product['offer'] == 'true'));

                $this->log($product['name'] . ' создается!!!\n');
            }
            */
        }
    }

    public function getProductsWithEmptyValues($ids, $values){
        $values = array("CODE");
        $el = new CIBlockElement();
        return $el -> GetList(
            array("ID"=>"ASC"),
            array("CODE" => false,
                  "ID" => $ids),
        );
    }

    public function createProduct($GUID, $name, $article, $priceArray, $offer=false){
        $el = new CIBlockElement;

        $arParams = array("replace_space"=>"-","replace_other"=>"-");
        $CODE = Cutil::translit($name,"ru",$arParams);
        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => $offer ? 3 : 2,
            "CODE" => $CODE,
            "PROPERTY_VALUES"=> array(
                "ARTNUMBER" => $article
            ),
            "NAME"           => $name,
            "ACTIVE"         => "N",            // неактивен
            "DETAIL_TEXT"    => '#BLANK#',
        );

        $result = $el->Add($arLoadProductArray);
        $GUIDcontrol = new GUIDController();
        $GUIDcontrol -> addGuid($offer ? 3: 2, $result, $GUID, $name);
        $this -> registerPrices($result, $priceArray);
    }

    public function fixEmptyGuids(){
        $GUIDcontrol = new GUIDController();
        $res = $GUIDcontrol ->getAllGuidsRes();
        while($rs = $res -> Fetch()){
         if (!$rs['UF_ELEMENT_ID']){
             $el = new CIBlockElement;
             $arLoadProductArray = Array(
                 "IBLOCK_SECTION_ID" => false,
                 "IBLOCK_ID"      => $rs['UF_IBLOCK_ID'],
                 "PROPERTY_VALUES"=> array(),
                 "NAME"           => $rs['UF_1C_PRODUCT_NAME'],
                 "ACTIVE"         => "N",            // неактивен
                 "DETAIL_TEXT"    => '#BLANK#',
             );

             $result = $el->Add($arLoadProductArray);
             $id = $rs['ID'];
             unset($rs['ID']);
             $rs['UF_ELEMENT_ID'] = $result;
             $GUIDcontrol -> addElementID($id, $rs);
         }
        }
    }

// Разбор данных для вывода
    private function parsingData($UNF, $UT)
    {
        for ($i = 0; $i <= count($UNF["ЗаказыУНФ"]); $i++) {
            if ($UNF["ЗаказыУНФ"][$i]["НомерНаСайте"]) $result[] = $UNF["ЗаказыУНФ"][$i]["НомерНаСайте"];
        }
        for ($i = 0; $i <= count($UT["ЗаказыУНФ"]); $i++) {
            if ($UT["ЗаказыУНФ"][$i]["НомерНаСайте"]) $result[] = $UT["ЗаказыУНФ"][$i]["НомерНаСайте"];
        }
        return $result;
    }

    public function rewriteGuid($ID, $IBLOCK_ID, $article)
    {
        $this -> start_log();
        //Полный список, полученный от базы 1С
        $catalog = $this -> GetCatalog();
        //Полный список, полученный от GUID

        //$this->log(print_r($catalog, true));
        foreach($catalog as $product_id => $product) {
            if ($product['article'] == $article){
                $GUIDcontrol = new GUIDController();
                $GUIDcontrol -> rewriteGuidId($product, $ID, $IBLOCK_ID);
                $this -> registerPrices($ID, array(
                        2 => $product['retail_price'],
                        3 => $product['trade_price']
                    ));
            }
        }
    }

// Запрос Curl Json
    private function CurlJsonPost($payload, $config)
    {
        print_r($payload);
        $username = $config['username'];
        $password = $config['password'];
        $credentials = base64_encode("$username:$password");
        $headers = [];
        $headers[] = "Authorization: Basic {$credentials}";
        $headers[] = "Content-Type:application/json";
        $curlDeal = curl_init();
        curl_setopt_array($curlDeal, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $config['link'],));

        curl_setopt($curlDeal, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlDeal, CURLOPT_POST, 1);
        curl_setopt($curlDeal, CURLOPT_POSTFIELDS, json_encode($payload));
        $resultQuery = curl_exec($curlDeal);
        print_r($resultQuery);
        return $resultQuery;
    }

// Получение массива GUID из 1C
    public function GetGuidJson($arArticle){
        // Форма массива: [{
        // "ID" => ID товара,
        // "IBLOCK_ID" => IBLOCK_ID товара,
        // "article" => артикул товара
        // }]
        $config = $this -> GetConfig();
        return $this -> CurlJsonPost($arArticle, $config);
    }

// Получение GUID из 1C
    public function UpdateGuid($article){
        // Форма массива: [{"ID" => ID товара, "IBLOCK_ID" => IBLOCK_ID товара, "article" => артикул товара}]

        $res = json_decode($this -> GetGuidJson(array(array("ID" => '', "IBLOCK_ID" => '', 'article' => $article))));
        print_r($res);
        $res = (array)$res[0];
        if($res && $res['Status']){
            return $res['GUID'];
        }
        else{
            return false;
        }
    }

    public function SetHandlers($handlers){
        $this -> handlers = $handlers;
    }

    public function RequestHandler(){
        // Получаем список хэдэров, находим функцию необходимого хэндлера
        $headers = getallheaders();
        $integration1C = Integration1C::getInstance();
        $arHandlers = $integration1C -> handlers;
        $idHandler = $headers['Subject'];
        if ($arHandlers[$idHandler]){
            $arHandlers[$idHandler]();
        }
        // TODO: Добавить логирование
    }

    //Функция для добавления хэндлера обработчика\
    public function Add1CHandler($idHandler, $func){
        $handlers = $this -> handlers;
        $handlers[$idHandler] = $func;
        $this -> SetHandlers($handlers);
    }

}