# Тинькофф Корзина Bitrix Module

<!-- TOC -->
* [Tinkoff Checkout Bitrix Module](#tinkoff-checkout-bitrix-module)
* [Настройка Тинькофф Корзины в личном кабинете](#настройка-тинькофф-корзины-в-личном-кабинете)
  * [Notification URL](#notification-url)
  * [IP адрес сервера](#ip-адрес-сервера)
* [Активация модуля](#активация-модуля)
* [Настройка модуля в интерфейсе Bitrix](#настройка-модуля-в-интерфейсе-bitrix)
  * [Базовая настройка](#базовая-настройка)
    * [Настройки магазина](#настройки-магазина)
    * [Настройки кнопки](#настройки-кнопки)
    * [Настройки соединения с API Tinkoff](#настройки-соединения-с-api-tinkoff)
  * [Расширенная настройка](#расширенная-настройка)
    * [Сбор и передача адреса](#сбор-и-передача-адреса)
      * [Налог на доставку и стоимость](#налог-на-доставку-и-стоимость)
    * [Интеграция со службами доставки (Metaship)](#интеграция-со-службами-доставки-metaship)
      * [Настройки Metaship](#настройки-metaship)
      * [Налог на доставку](#налог-на-доставку)
      * [Объявленная стоимость](#объявленная-стоимость)
* [Руководство разработчика](#руководство-разработчика)
  * [Ручная установка модуля (не через маркетплейс)](#ручная-установка-модуля-не-через-маркетплейс)
  * [Отображение кнопки](#отображение-кнопки)
  * [События](#события)
  * [Объекты](#объекты)
    * [OrderUpdate. Данные для обновления заказа](#orderupdate-данные-для-обновления-заказа)
  * [Новые сущности](#новые-сущности)
    * [Тип магазина](#тип-магазина)
    * [Поля заказа](#поля-заказа)
    * [Доставки](#доставки)
    * [Статусы заказов](#статусы-заказов)
<!-- TOC -->

# Настройка Тинькофф Корзины в личном кабинете

Подробная инструкция по настройке доступна по адресу:

https://www.tinkoff.ru/business/help/checkout-connect/

### Notification URL

В зависимости от CMS, адрес может иметь следующий вид:

1. Bitrix: https://example.com/bitrix/services/main/ajax.php?action=tinkoff:checkout.checkout.updateOrderStatus
2. WordPress: https://example.com/wp-json/tinkoff_checkout/v1/updateOrder

Где вместо `example.com` необходимо указать домен магазина

### IP адрес сервера
IP Адрес можно узнать на сайте 2ip:
https://2ip.ru/whois/

# Активация модуля

После добавления модуля на сайт, необходимо предварительно активировать его по следующему пути:<br>
**Marketplace / Установленные расширения**

В списке модулей необходимо найти модуль **Тинькофф Корзина** и нажать **Установить**

# Настройка модуля в интерфейсе Bitrix

## Базовая настройка

Настройки модуля находятся по следующему пути: <br>
**Настройки / Настройки продукта / Настройки модулей / Тинькофф Корзина**

Основные настройки делятся на 3 группы:

1. Настройки магазина
2. Настройки кнопки
3. Настройки соединения с API Tinkoff

### Настройки магазина

В настройках магазина указывается:

1. Тип налогообложения магазина
2. Тип магазина. Должен быть указать как в личном кабинете Тинькофф Корзина
3. Полный URL страницы успеха. Страница куда попадает пользователь после оплаты

### Настройки кнопки

В настройках кнопки указываются визуальные параметры самой кнопки Тинькофф Корзины:

1. Тема. Для какого фона отображать кнопку: темного или светлого
2. Фон
3. Ширина, высота
4. Скругление
5. Логотип. Отображать ли логотип Tinkoff на кнопке

Наглядно можно протестировать настройки на странице описания модуля:
https://www.tinkoff.ru/business/help/checkout-connect/?card=q4

### Настройки соединения с API Tinkoff

Все данные для заполнения полей в этой группе необходимо получить через личный кабинет Тинькофф Корзины.
Подробная инструкция доступна по адресу: https://www.tinkoff.ru/business/help/checkout-connect/

1. Идентификатор магазина
2. Токен авторизации. Привязывается к IP адресу магазина
3. Путь на сервере до сертификата MTLS. Можно загрузить через FTP и указать путь или выбрать файл с компьютера
4. Путь на сервере до приватного ключа. Можно загрузить через FTP и указать путь или выбрать файл с компьютера

## Расширенная настройка

Если выбрать в пункте "Тип магазина" одно из следующих значений:

1. Сбор и передача адреса
2. Интеграция со службами доставки (Metaship)

То будут доступны расширенные настройки доставки

### Сбор и передача адреса

#### Налог на доставку и стоимость

Настройка данного типа доставки осуществляется по следующему пути:

Магазин / Службы доставки / Тинькофф Корзина. Сбор и передача адреса

Важными настройками являются:

1. Ставка НДС. Передается в API для корректного подсчета налогов
2. Настройки обработчика / Цена. Необходимо указать стоимость доставки, которая попадет в API

### Интеграция со службами доставки (Metaship)

#### Настройки Metaship

Для корректной работы Metaship, необходимо настроить следующие поля:

1. ID магазина Metaship. Получается в личном кабинете Metaship
2. ID склада Metaship. Получается в личном кабинете Metaship
3. Типы доставки. Необходимо указать типы доставки для агрегатора Metaship

#### Налог на доставку

Настройка данного типа доставки осуществляется по следующему пути:

Магазин / Службы доставки / Тинькофф Корзина. Интеграция со службами доставки

Важными настройками является:

1. Ставка НДС. Передается в API для корректного подсчета налогов

#### Объявленная стоимость

В настройках товара можно указать объявленную стоимость товара. Для этого необходимо перейти в нужный товар и в
одноименном поле указать значение в рублях

# Руководство разработчика

## Ручная установка модуля (не через маркетплейс)

Для установки вручную, необходимо скачать архив с кодом модуля и загрузить его на сайт в
директорию `/bitrix/modules/tinkoff.checkout`

Важно, чтобы директория с модулем называлась именно **tinkoff.checkout**

По итогу, у вас будут файлы модуля, включая файл `/bitrix/modules/tinkoff.checkout/install/index.php`

## Отображение кнопки

Для отображения кнопки нужно использовать следующий код:

```php
<?php $APPLICATION->IncludeComponent("tinkoff:checkout.button","");?>
```

В параметрах кнопки можно дополнительно указать следующие параметры:

| Параметр                              | Описание               |
|---------------------------------------|------------------------|
| tinkoff_checkout_button_theme         | Для какого фона кнопка |
| tinkoff_checkout_button_background    | Фон кнопки             |
| tinkoff_checkout_button_width         | Ширина кнопки          |
| tinkoff_checkout_button_height        | Высота кнопки          |
| tinkoff_checkout_button_border_radius | Скругление кнопки      |
| tinkoff_checkout_button_is_show_logo  | Отображать ли логотип  |

Данные параметры используются в большем приоритете, нежели определенные в настройках модуля
Наглядно можно протестировать настройки на странице описания модуля:
https://www.tinkoff.ru/business/help/checkout-connect/?card=q4

## События

Для расширенной настройки модуля, доступны следующие события

| Событие                                 | Описание                                                                                                                                                                 | Аргументы                                                                                                            |
|-----------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------|
| OnBeforeTinkoffCheckoutInvoiceCreate    | Вызывается при клике на кнопку Tinkoff Checkout до создания заказа Bitrix и заказ Tinkoff Checkout. Можно использовать для редактирования корзины                        | -                                                                                                                    |
| OnTinkoffCheckoutInvoiceCreateViaAPI    | Вызывается до момент отправки запроса по API Tinkoff, но после полного формирования тела запроса и заказа внутри Bitrix. Можно отредактировать поля, отправляемые на API | 1. `&$body \| array` - тело запроса                                                                                  |
| OnAfterTinkoffCheckoutInvoiceCreate     | Вызывается после отправки запроса по API Tinkoff                                                                                                                         | 1. `$body \| array` - тело запроса <br/>2. `$response \| array` - ответ запроса                                      |
| OnBeforeTinkoffCheckoutOrderUpdate      | Вызывается до обновления заказа по данным пришедшим с Tinkoff API                                                                                                        | 1. `&$update \| object` - OrderUpdate (описание ниже)                                                                |
| OnTinkoffCheckoutOrderUpdate            | Вызывается после проверки существования заказа для обновления, но до обновление его объекта                                                                              | 1. `&$order \| object` - объект заказа Bitrix <br/>2. `&$update` - OrderUpdate (описание ниже)                       |
| OnAfterTinkoffCheckoutOrderUpdate       | Вызывается после обновления заказа и сохранения данных по нему                                                                                                           | 1. `&$order \| object` - объект заказа Bitrix                                                                        |
| OnBeforeTinkoffCheckoutItemAddToInvoice | Вызывается при формировании массива товаров для API Tinkoff                                                                                                              | 1. `&$item \| array` - массив данных сформированного товара                                                          |
| OnTinkoffCheckoutGetDelivery            | Вызывается при получении ID метода доставки из модуля Tinkoff Checkout                                                                                                   | 1. `&$entity \| object` - объект доставки <br/>2. `$type \| string` - тип доставки by_merchant или by_service        |
| OnTinkoffCheckoutGetDeliveryPrice       | Вызывается при получении цены доставки по ее типу. Если поменять доставку в событии OnTinkoffCheckoutGetDelivery, то в данном событии цена изменится тоже                | 1. `&$price \| float` - цена доставки <br/>2. `$type \| string` - тип доставки by_merchant или by_service            |
| OnTinkoffCheckoutGetDeliveryVatRate     | Вызывается при получении налога на доставку по ее типу. Если поменять доставку в событии OnTinkoffCheckoutGetDelivery, то в данном событии налог изменится тоже          | 1. `&$rate \| float` - множитель налога доставки <br/>2. `$type \| string` - тип доставки by_merchant или by_service |
| OnTinkoffCheckoutGetProductWeight       | Вызывается при получении веса товара                                                                                                                                     | 1. `&$weight \| float` - вес товара <br/>2. `$product \| object` - Объект Bitrix BasketItemBase                      |
| OnTinkoffCheckoutGetProductHeight       | Вызывается при получении высоты товара                                                                                                                                   | 1. `&height \| float` - высота товара <br/>2. `$product \| object` - Объект Bitrix BasketItemBase                    |
| OnTinkoffCheckoutGetProductWidth        | Вызывается при получении ширины товара                                                                                                                                   | 1. `&width \| float` - ширина товара <br/>2. `$product \| object` - Объект Bitrix BasketItemBase                     |
| OnTinkoffCheckoutGetProductLength       | Вызывается при получении длины товара                                                                                                                                    | 1. `&length \| float` - длина товара <br/>2. `$product \| object` - Объект Bitrix BasketItemBase                     |

## Объекты

### OrderUpdate. Данные для обновления заказа

Исходный файл: `./src/TinkoffApi/Update/Order`

Методы:

| Метод                                         | Описание                                                                                                                                         |
|-----------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------|
| `getRequest():array`                          | Получение текущего тела запроса                                                                                                                  |
| `setRequest($request):void`                   | Установка текущего тела запроса                                                                                                                  |
| `getOrderID():?int`                           | Получение ID заказа в Bitrix                                                                                                                     |
| `setOrderID($orderID):void`                   | Установка ID заказа Bitrix                                                                                                                       |
| `getOrderStatus():string`                     | Получение ID статуса заказа. Таблица b_sale_status_lang содержит список статусов с их описанием                                                  |
| `setOrderStatus($orderStatus):void`           | Установка ID статуса заказа. Таблица b_sale_status_lang содержит список статусов с их описанием                                                  |
| `getIsPaid():string`                          | Получение флага(Y/N) был ли оплачен заказ                                                                                                        |
| `setIsPaid($isPaid):void`                     | Установка флага(Y/N) был ли оплачен заказ                                                                                                        |
| `isHandleOrder():bool`                        | Был ли запрос на дополнительные(почта, телефон, фио, адрес) данные по заказу. В случае false детальные данные по заказу не обновляются           |
| `setIsHandleOrder($isHandleOrder):void`       | Установка был ли запрос на дополнительные(почта, телефон, фио, адрес) данные по заказу. В случае false детальные данные по заказу не обновляются |
| `getOrderData():array`                        | Получение массива данных после дополнительного запроса по заказу                                                                                 |
| `setOrderData($orderData):void`               | Установка дополнительных данных после дополнительного запроса по заказу                                                                          |
| `getIsDelivered():string`                     | Получение флага(Y/N) был ли заказ доставлен                                                                                                      |
| `setIsDelivered($isDelivered):void`           | Установка флага(Y/N) был ли заказ доставлен                                                                                                      |
| `getIsEnableDelivery():string`                | Получение флага(Y/N) доступна ли доставка                                                                                                        |
| `setIsEnableDelivery($isEnableDelivery):void` | Установка флага(Y/N) доступна ли доставка                                                                                                        |
| `getIsMarked():string`                        | Получение флага(Y/N) была ли проблема с заказом                                                                                                  |
| `setIsMarked($isMarked):void`                 | Установка флага(Y/N) была ли проблема с заказом                                                                                                  |

Детальное описание дополнительного запроса по заказу:
https://developer.tinkoff.ru/docs/api/get-api-v-2-orders-shop-id-order-id

## Новые сущности

### Тип магазина

1. by_merchant. Сбор и передача адреса
2. by_service. Интеграция со службами доставки (Metaship)

### Поля заказа

1. Объявленная стоимость. Поле используется в магазине типа by_service

### Доставки

1. tinkoff_checkout_by_merchant. Доставка для магазина by_merchant. Устанавливается системно
2. tinkoff_checkout_by_service. Доставка для магазина by_service. Устанавливается системно

### Статусы заказов

1. TD. Metaship, обработка заявки
2. TS. Metaship, ожидает отправки на склад
3. TF. Metaship, ошибка обработки заявки
4. TN. Metaship, заявка не создалась

















