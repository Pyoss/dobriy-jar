<?php

use TinkoffCheckout\Entities\Delivery;
use TinkoffCheckout\Entities\Vat;
use TinkoffCheckout\Helpers\StringConvertor;
use TinkoffCheckout\Settings\Builders\FieldBuilder;
use TinkoffCheckout\Settings\Builders\SettingsTabsBuilder;
use TinkoffCheckout\Settings\Builders\TabBuilder;
use TinkoffCheckout\Settings\Helpers\SettingsFields;
use TinkoffCheckout\Settings\Helpers\SettingsValidator;
use TinkoffCheckout\Settings\ProcessResponse;

require_once __DIR__ . '/include.php';

if (!CModule::IncludeModule(TINKOFF_CHECKOUT_MODULE_ID)) {
    return;
}

if (!isset($APPLICATION)) {
    return;
}

$sites       = [];
$sitesObject = CSite::GetList();
$fields      = [];
$files       = [];
while ($site = $sitesObject->Fetch()) {
    if ($site['ACTIVE'] !== 'Y') {
        continue;
    }

    $sites[$site['LID']] = [
        'id'           => $site['LID'],
        'name_private' => $site['NAME'],
        'name_public'  => $site['SITE_NAME'],
        'name'         => '(' . $site['LID'] . ')' . ' ' . $site['SITE_NAME']
    ];

    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_SHOP_ID, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_TOKEN, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_MTLS_CERT, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_MTLS_PRIVATE, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_TAXATION, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_ITEM_TAX_DELIVERY, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_REDIRECT_URL, $site['LID']);

    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_THEME, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_BACKGROUND, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_WIDTH, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_HEIGHT, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_BORDER_RADIUS, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_IS_SHOW_LOGO, $site['LID']);

    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_SHOP_ID, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_WAREHOUSE_ID, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_TYPES, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_WEIGHT, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_HEIGHT, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_WIDTH, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_LENGTH, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_PRICE, $site['LID']);

    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_TYPE, $site['LID']);
    $fields[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_PRICE, $site['LID']);

    $files[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_MTLS_CERT, $site['LID']);
    $files[] = SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_MTLS_PRIVATE, $site['LID']);
}
unset($site);

$right = $APPLICATION->GetGroupRight("subscribe");
if ($right == "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}


if (isset($save) || isset($apply) || isset($RestoreDefaults)) {
    $save            = isset($save) && $save ? $save : '';
    $apply           = isset($apply) && $apply ? $apply : '';
    $RestoreDefaults = isset($RestoreDefaults) && $RestoreDefaults ? $RestoreDefaults : '';

    ProcessResponse::process(
        TINKOFF_CHECKOUT_MODULE_ID,
        $fields,
        [
            $save,
            $apply,
            $RestoreDefaults
        ],
        $files,
        function ($value, $fieldID, $moduleID) {
            $sizesFields = [
                TINKOFF_CHECKOUT_FIELD_DELIVERY_HEIGHT,
                TINKOFF_CHECKOUT_FIELD_DELIVERY_WIDTH,
                TINKOFF_CHECKOUT_FIELD_DELIVERY_LENGTH,
            ];
            foreach ($sizesFields as $key) {
                $value = SettingsValidator::float($fieldID, $key, $value, 1, 1000);
            }


            $value = SettingsValidator::float($fieldID, TINKOFF_CHECKOUT_FIELD_DELIVERY_WEIGHT, $value, 0.005, 100);
            $value = SettingsValidator::float($fieldID, TINKOFF_CHECKOUT_BUTTON_FIELD_WIDTH, $value, 100, 600);
            $value = SettingsValidator::float($fieldID, TINKOFF_CHECKOUT_BUTTON_FIELD_HEIGHT, $value, 40, 60);
            $value = SettingsValidator::float($fieldID, TINKOFF_CHECKOUT_BUTTON_FIELD_BORDER_RADIUS, $value, 0, 40);

            if (SettingsFields::is($fieldID, TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_TYPES)) {
                $value = json_decode($value) ? json_decode($value, true) : $value;
                $value = is_array($value) ? array_unique($value) : [];
                $value = json_encode($value);
            }

            return $value;
        }
    );
}


$settingsTabs = new SettingsTabsBuilder(TINKOFF_CHECKOUT_MODULE_ID);
$settingsTabs->setHeadline('Настройки модуля Tinkoff Checkout');
foreach ($sites as $id => $site) {
    $tabMain = new TabBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $tabMain->setId('main_' . $id);
    $tabMain->setName($site['name']);
    $tabMain->setHeadline('Тинькофф Корзина');


    // Основные настройки магазина
    $heading = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $heading->setType(FieldBuilder::TYPE_HEADING);
    $heading->setHeading('Настройки магазина');
    $tabMain->addField($heading);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_TAXATION, $id));
    $field->setLabel('Система налогообложения');
    $field->setType(FieldBuilder::TYPE_SELECT);
    $field->setOptions([
        'osn'                => 'Общая система налогообложения (ОСН)',
        'usn_income'         => 'Упрощенная СН (УСН)',
        'usn_income_outcome' => 'Упрощенная СН (доходы минус расходы)',
        'envd'               => 'Единый налог на вмененный доход',
        'esn'                => 'Единый сельскохозяйственный налог',
        'patent'             => 'Патентная СН',
        'self'               => 'ИП на НПД (налог на профессиональный доход)',
    ]);
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_TYPE, $id));
    $field->setLabel('Тип магазина');
    $field->setType(FieldBuilder::TYPE_SELECT);
    $field->setOptions([
        'disable'                        => 'Услуги',
        Delivery::DELIVERY_TYPE_MERCHANT => 'Сбор и передача адреса',
        Delivery::DELIVERY_TYPE_METASHIP => 'Интеграция со службами доставки (Metaship)',
    ]);
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_REDIRECT_URL, $id));
    $field->setLabel('Полный URL страницы успеха');
    $field->setType(FieldBuilder::TYPE_TEXT);
    $field->setPlaceholder('https://example.com');
    $tabMain->addField($field);


    // Сбор и передача адреса
//    $heading = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
//    $heading->setType(FieldBuilder::TYPE_HEADING);
//    $heading->setHeading('Сбор и передача адреса');
//    $heading->setClass('tinkoff-checkout-by-merchant');
//    $tabMain->addField($heading);
//
//    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
//    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_PRICE, $id));
//    $field->setLabel('Цена доставки, коп');
//    $field->setType(FieldBuilder::TYPE_TEXT);
//    $field->setPlaceholder('Цена доставки в копейках');
//    $field->setClass('tinkoff-checkout-by-merchant');
//    $tabMain->addField($field);
//
//    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
//    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_ITEM_TAX_DELIVERY, $id));
//    $field->setLabel('Cтавка НДС для доставки');
//    $field->setType(FieldBuilder::TYPE_SELECT);
//    $field->setClass('tinkoff-checkout-by-merchant');
//    $field->setOptions([
//        Vat::RATE_NONE          => 'Без НДС',
//        Vat::VAT_ZERO_PERCENT   => '0%',
//        Vat::VAT_TEN_PERCENT    => '10%',
//        Vat::VAT_TWENTY_PERCENT => '20%',
////        'vat110' => '10/110',
////        'vat118' => '20/120',
//    ]);
//    $tabMain->addField($field);

    // Metaship (by_service)
    $heading = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $heading->setType(FieldBuilder::TYPE_HEADING);
    $heading->setHeading('Интеграция со службами доставки (Metaship)');
    $heading->setClass('tinkoff-checkout-by-service');
    $tabMain->addField($heading);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_SHOP_ID, $id));
    $field->setLabel('ID магазина Metaship');
    $field->setType(FieldBuilder::TYPE_TEXT);
    $field->setPlaceholder('ID магазина Metaship');
    $field->setClass('tinkoff-checkout-by-service');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_WAREHOUSE_ID, $id));
    $field->setLabel('ID склада Metaship');
    $field->setType(FieldBuilder::TYPE_TEXT);
    $field->setPlaceholder('ID склада Metaship');
    $field->setClass('tinkoff-checkout-by-service');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_TYPES, $id) . '[]');
    $field->setValue('Courier');
    $field->setLabel('Курьерская доставка - Тип доставки');
    $field->setType(FieldBuilder::TYPE_CHECKBOX);
    $field->setClass('tinkoff-checkout-by-service');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_TYPES, $id) . '[]');
    $field->setValue('PostOffice');
    $field->setLabel('Почтовое отправление - Тип доставки');
    $field->setType(FieldBuilder::TYPE_CHECKBOX);
    $field->setClass('tinkoff-checkout-by-service');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_TYPES, $id) . '[]');
    $field->setValue('DeliveryPoint');
    $field->setLabel('ПВЗ - Тип доставки');
    $field->setType(FieldBuilder::TYPE_CHECKBOX);
    $field->setClass('tinkoff-checkout-by-service');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_WEIGHT, $id));
    $field->setLabel('Вес, кг');
    $field->setType(FieldBuilder::TYPE_TEXT);
    $field->setPlaceholder('От 0.005 до 100.000');
    $field->setClass('tinkoff-checkout-by-service');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_HEIGHT, $id));
    $field->setLabel('Высота, см');
    $field->setType(FieldBuilder::TYPE_TEXT);
    $field->setPlaceholder('От 1 до 1000');
    $field->setClass('tinkoff-checkout-by-service');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_WIDTH, $id));
    $field->setLabel('Ширина, см');
    $field->setType(FieldBuilder::TYPE_TEXT);
    $field->setPlaceholder('От 1 до 1000');
    $field->setClass('tinkoff-checkout-by-service');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_LENGTH, $id));
    $field->setLabel('Длина, см');
    $field->setType(FieldBuilder::TYPE_TEXT);
    $field->setPlaceholder('От 1 до 1000');
    $field->setClass('tinkoff-checkout-by-service');
    $tabMain->addField($field);

//    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
//    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_PRICE, $id));
//    $field->setLabel('Объявленная стоимость, коп');
//    $field->setType(FieldBuilder::TYPE_TEXT);
//    $field->setPlaceholder('Объявленная стоимость, коп');
//    $field->setClass('tinkoff-checkout-by-service');
//    $tabMain->addField($field);


    // Настройки кнопки
    $heading = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $heading->setType(FieldBuilder::TYPE_HEADING);
    $heading->setHeading('Настройки кнопки');
    $tabMain->addField($heading);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_THEME, $id));
    $field->setLabel('Тема');
    $field->setType(FieldBuilder::TYPE_SELECT);
    $field->setOptions([
        'LIGHT' => 'Для светлого фона',
        'DARK'  => 'Для темного фона',
    ]);
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_BACKGROUND, $id));
    $field->setLabel('Фон');
    $field->setType(FieldBuilder::TYPE_SELECT);
    $field->setOptions([
        'BLACK'         => 'Черный',
        'GRAY'          => 'Серый',
        'WHITE_OUTLINE' => 'Контурный',
    ]);
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_WIDTH, $id));
    $field->setLabel('Ширина');
    $field->setType(FieldBuilder::TYPE_NUMBER);
    $field->setMin(100);
    $field->setMin(600);
    $field->setPlaceholder('Ширина кнопки в px, от 100 до 600');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_HEIGHT, $id));
    $field->setLabel('Высота');
    $field->setType(FieldBuilder::TYPE_NUMBER);
    $field->setMin(40);
    $field->setMin(60);
    $field->setPlaceholder('Высота кнопки в px, от 40 до 60');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_BORDER_RADIUS, $id));
    $field->setLabel('Скругление');
    $field->setType(FieldBuilder::TYPE_NUMBER);
    $field->setMin(0);
    $field->setMin(30);
    $field->setPlaceholder('Скругление кнопки в px, от 0 до 40');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_BUTTON_FIELD_IS_SHOW_LOGO, $id));
    $field->setLabel('Логотип Тинькофф');
    $field->setType(FieldBuilder::TYPE_SELECT);
    $field->setOptions([
        'true'  => 'Показать',
        'false' => 'Скрыть',
    ]);
    $field->setStrictValue(true);
    $tabMain->addField($field);


    // Настройки связи с Tinkoff API
    $heading = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $heading->setType(FieldBuilder::TYPE_HEADING);
    $heading->setHeading('Настройки соединения с API Tinkoff');
    $tabMain->addField($heading);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_SHOP_ID, $id));
    $field->setLabel('Идентификатор магазина');
    $field->setType(FieldBuilder::TYPE_TEXT);
    $field->setPlaceholder('Ваш shop id');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_TOKEN, $id));
    $field->setLabel('Токен авторизации');
    $field->setType(FieldBuilder::TYPE_TEXT);
    $field->setPlaceholder('Ваш Bearer токен');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_MTLS_CERT, $id));
    $field->setLabel('Путь на сервере до сертификата MTLS');
    $field->setType(FieldBuilder::TYPE_FILE);
    $field->setPlaceholder('Полный путь по MTLS сертификата');
    $tabMain->addField($field);

    $field = new FieldBuilder(TINKOFF_CHECKOUT_MODULE_ID);
    $field->setId(SettingsFields::getFieldName(TINKOFF_CHECKOUT_FIELD_MTLS_PRIVATE, $id));
    $field->setLabel('Путь на сервере до приватного ключа');
    $field->setType(FieldBuilder::TYPE_FILE);
    $field->setPlaceholder('Полный путь по приватного ключа');
    $tabMain->addField($field);


    $settingsTabs->addTab($tabMain);
}
?>

<style>
    #tinkoff-checkout select[multiple] {
        min-height: 27px;
        height: auto;
    }

    #tinkoff-checkout .adm-detail-content-item-block input,
    #tinkoff-checkout .adm-detail-content-item-block select {
        width: 100%;
        max-width: 450px;
        display: block;
        box-sizing: border-box;
    }

    #tinkoff-checkout input[type="text"],
    #tinkoff-checkout select {
        height: 31px;
    }

    #tinkoff-checkout .adm-input-file {
        margin-top: 6px;
    }

    #tinkoff-checkout .adm-detail-content-cell-r {
        padding: 8px 0 11px 4px;
    }

    /* Tinkoff Checkout Styles */
    .tinkoff-checkout-by-merchant, .tinkoff-checkout-by-service {
        display: none;
    }
</style>

<div id='tinkoff-checkout'>
    <?php
    $settingsTabs->build($APPLICATION); ?>
</div>

<script>
  updateDeliveryNodes()

  document.querySelectorAll('[name^="tinkoff_checkout_delivery_type"]').forEach(function (select) {
    select.addEventListener('change', updateDeliveryNodes)
  })

  function updateDeliveryNodes () {
    const tabContentNodes = document.querySelectorAll('.adm-detail-content')
    tabContentNodes.forEach(function (tabNode) {
      const deliveryType = tabNode.querySelector('[name^="tinkoff_checkout_delivery_type"]').value
      if (deliveryType === 'disable') {
        nodesHide(tabNode, '.tinkoff-checkout-by-merchant')
        nodesHide(tabNode, '.tinkoff-checkout-by-service')
        return
      }

      if (deliveryType === 'by_merchant') {
        nodesShow(tabNode, '.tinkoff-checkout-by-merchant')
        nodesHide(tabNode, '.tinkoff-checkout-by-service')
      }

      if (deliveryType === 'by_service') {
        nodesShow(tabNode, '.tinkoff-checkout-by-service')
        nodesHide(tabNode, '.tinkoff-checkout-by-merchant')
      }
    })

    function nodesShow (parent, query) {
      updateNodesDisplay(parent, query, 'table-row')
    }

    function nodesHide (parent, query) {
      updateNodesDisplay(parent, query, 'none')
    }

    function updateNodesDisplay (parent, query, display) {
      parent.querySelectorAll(query).forEach(function (node) {
        node.style.display = display
      })
    }
  }
</script>