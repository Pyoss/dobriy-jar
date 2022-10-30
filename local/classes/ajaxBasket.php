<?php

/**
 * Class AjaxBasket
 * Для асинхронной работы с корзиной
 */
class AjaxBasket extends AjaxRes {

    /**
     * @param $productId - айди товара в каталоге
     * @param $quantity - количество добавляемого товара
     * @param $provide_response - нужен ли ответ json
     * Добавление товара по айди. Отправляет JSON
     * status => boolean
     * info => array
     */
    public function addItem($productId, $quantity, $provide_response){
        $basket = $this->getUserBasket();
        if ($item_obj = $basket->getExistsItem('catalog', $productId)) {
            $result = $item_obj->setField('QUANTITY', $item_obj->getQuantity() + $quantity);
        } else {
            $item_obj = $basket->createItem('catalog', $productId);
            $result = $item_obj->setFields([
                'QUANTITY' => $quantity,
                'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
            ]);
        }
        if ($errors = $result->getErrors()) {
            $this -> actionComplete = false;
            $this -> jsonResponse['error_message'] = $errors[0]->getMessage();
        } else {
            $this -> actionComplete = true;
        }
        $basket -> save();

        if ($provide_response) {
            $this->applyDiscounts($basket);
            $item_obj = $basket->getExistsItem('catalog', $productId);

            $this->jsonResponse['basket'] = $this->dumpBasketJson($basket);
            $this->jsonResponse['item'] = $this->dumpItemJson($item_obj);
            $this->sendJsonResponse();
        }
    }

    /**
     * @param $productId - айди товара в каталоге
     * @param $quantity - новое количество товара
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public function updateItem($productId, $quantity, $provide_response){
        $basket = $this->getUserBasket();
        if ($item_obj = $basket->getExistsItem('catalog', $productId)) {
            $result = $item_obj->setField('QUANTITY', $quantity);
        } else {
            $item_obj = $basket->createItem('catalog', $productId);
            $result = $item_obj->setFields([
                'QUANTITY' => $quantity,
                'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
            ]);
        }
        if ($errors = $result->getErrors()) {
            $this -> actionComplete = false;
            $this -> jsonResponse['error_message'] = $errors[0]->getMessage();
        } else {
            $this -> actionComplete = true;
        }
        $basket -> save();

        if ($provide_response) {
            $this->applyDiscounts($basket);
            $item_obj = $basket->getExistsItem('catalog', $productId);

            $this->jsonResponse['basket'] = $this->dumpBasketJson($basket);
            $this->jsonResponse['item'] = $this->dumpItemJson($item_obj);
            $this->sendJsonResponse();
        }
    }

    /**
     * Удаляет товар из корзины по айди
     * @param $productId - айди товара в каталоге
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectNotFoundException
     * @throws \Bitrix\Main\NotImplementedException
     */
    public function deleteItem($productId, $provide_response){
        $basket = $this->getUserBasket();
        $item_obj = $basket->getExistsItem('catalog', $productId);
        if ($item_obj) {
            $this -> jsonResponse['deleted_item'] = $this->dumpItemJson($item_obj);
            $item_obj -> delete();
            $this -> actionComplete = true;
        } else {
            $this -> jsonResponse['deleted_item'] = ['PRODUCT_ID' => $productId];
            $this -> actionComplete = false;
            $this -> jsonResponse['error_message'] = 'Product not found';
        }
        $basket -> save();

        if ($provide_response) {
            if (!$basket->count()) {
                $this->jsonResponse['basket'] = 'empty';
            } else {
                $this->applyDiscounts($basket);
                $this->jsonResponse['basket'] = $this->dumpBasketJson($basket);
            }
            $this->sendJsonResponse();
        }
    }

    /**
     * @param $basket
     * @throws \Bitrix\Main\InvalidOperationException
     */
    public function applyDiscounts($basket){
        $context = new \Bitrix\Sale\Discount\Context\Fuser($basket->getFUserId());
        $discounts = \Bitrix\Sale\Discount::buildFromBasket($basket, $context);
        $r = $discounts->calculate();// Проверял, в $r есть нужные мне скидки

        if (!$r->isSuccess())
        {
            var_dump($r->getErrorMessages());
        }

        $result = $r->getData();// var_dump($result); показывает что ключа ['BASKET_ITEMS'] в нём нет

        if (isset($result['BASKET_ITEMS'])) {// из комментария выше следует что следующий код выполнен не будет
            $r = $basket->applyDiscount($result['BASKET_ITEMS']);
            if (!$r->isSuccess())
            {
                var_dump($r->getErrorMessages());
            }
        }


    }
    public function dumpBasketJson($basket): array
    {
        $arBasketData = [
            'PRICE' => $basket -> getPrice(),
            'BASE_PRICE' => $basket -> getBasePrice(),
            'QUANTITY' => array_sum($basket -> getQuantityList())
        ];
        return $arBasketData;
    }

    public function dumpItemJson($item){
        $arItemData = [
            'PRICE' => $item -> getPrice(),
            'BASE_PRICE' => $item -> getBasePrice(),
            'QUANTITY' => $item -> getQuantity(),
            'PRODUCT_ID' => $item -> getProductId()
        ];
        return $arItemData;
    }

    /**
     * Удаляет товар из корзины по айди в корзине
     * @param $productId - айди товара в корзине
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectNotFoundException
     * @throws \Bitrix\Main\NotImplementedException
     */
    public function deleteBasketItem($productId){
        $basket = $this->getUserBasket();
        $result = $basket->getItemById($productId)->delete();
        if ($errors = $result->getErrors()) {
            $this -> actionComplete = false;
            $this -> jsonResponse['error_message'] = $errors[0]->getMessage();
        } else {
            $this -> actionComplete = true;
            $this -> jsonResponse = 'Deleted';
        }
        $this -> sendJsonResponse();
    }

    /**
     * Достает корзину текущего пользователя
     * @return \Bitrix\Sale\BasketBase
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\NotImplementedException
     */
    public function getUserBasket(): \Bitrix\Sale\BasketBase
    {
        return Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(),
            Bitrix\Main\Context::getCurrent()->getSite());
    }

    /**
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\NotImplementedException
     */
    public function getBasketLength (){
        $GLOBALS['LOCAL']['BASKET'] = array_sum($this -> getUserBasket() -> getQuantityList());
        return $GLOBALS['LOCAL']['BASKET'];
    }


}





