<?php

require_once __DIR__ . '/autoload.php';

// Название модуля
const TINKOFF_CHECKOUT_MODULE_ID = 'tinkoff.checkout';

// Поля настроек
const TINKOFF_CHECKOUT_FIELD_SHOP_ID           = 'tinkoff_checkout_shop_id';
const TINKOFF_CHECKOUT_FIELD_TOKEN             = 'tinkoff_checkout_token';
const TINKOFF_CHECKOUT_FIELD_MTLS_CERT         = 'tinkoff_checkout_mtls_cert';
const TINKOFF_CHECKOUT_FIELD_MTLS_PRIVATE      = 'tinkoff_checkout_mtls_private';
const TINKOFF_CHECKOUT_FIELD_TAXATION          = 'tinkoff_checkout_taxation';
const TINKOFF_CHECKOUT_FIELD_ITEM_TAX_DELIVERY = 'tinkoff_checkout_tax_delivery';
const TINKOFF_CHECKOUT_FIELD_REDIRECT_URL      = 'tinkoff_checkout_redirect_url';

const TINKOFF_CHECKOUT_BUTTON_FIELD_THEME         = 'tinkoff_checkout_button_theme';
const TINKOFF_CHECKOUT_BUTTON_FIELD_BACKGROUND    = 'tinkoff_checkout_button_background';
const TINKOFF_CHECKOUT_BUTTON_FIELD_WIDTH         = 'tinkoff_checkout_button_width';
const TINKOFF_CHECKOUT_BUTTON_FIELD_HEIGHT        = 'tinkoff_checkout_button_height';
const TINKOFF_CHECKOUT_BUTTON_FIELD_BORDER_RADIUS = 'tinkoff_checkout_button_border_radius';
const TINKOFF_CHECKOUT_BUTTON_FIELD_IS_SHOW_LOGO  = 'tinkoff_checkout_button_is_show_logo';

const TINKOFF_CHECKOUT_FIELD_DELIVERY_TYPE  = 'tinkoff_checkout_delivery_type';
const TINKOFF_CHECKOUT_FIELD_DELIVERY_PRICE = 'tinkoff_checkout_delivery_price';

const TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_SHOP_ID      = 'tinkoff_checkout_delivery_metaship_shop_id';
const TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_WAREHOUSE_ID = 'tinkoff_checkout_delivery_metaship_warehouse_id';
const TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_TYPES        = 'tinkoff_checkout_delivery_metaship_types';
const TINKOFF_CHECKOUT_FIELD_DELIVERY_WEIGHT                = 'tinkoff_checkout_delivery_weight';
const TINKOFF_CHECKOUT_FIELD_DELIVERY_HEIGHT                = 'tinkoff_checkout_delivery_height';
const TINKOFF_CHECKOUT_FIELD_DELIVERY_WIDTH                 = 'tinkoff_checkout_delivery_width';
const TINKOFF_CHECKOUT_FIELD_DELIVERY_LENGTH                = 'tinkoff_checkout_delivery_length';
const TINKOFF_CHECKOUT_FIELD_DELIVERY_METASHIP_PRICE        = 'tinkoff_checkout_delivery_metaship_price';

//События
const TINKOFF_CHECKOUT_ACTION_BEFORE_INVOICE_CREATE  = 'OnBeforeTinkoffCheckoutInvoiceCreate';
const TINKOFF_CHECKOUT_ACTION_INVOICE_CREATE_VIA_API = 'OnTinkoffCheckoutInvoiceCreateViaAPI';
const TINKOFF_CHECKOUT_ACTION_AFTER_INVOICE_CREATE   = 'OnAfterTinkoffCheckoutInvoiceCreate';

const TINKOFF_CHECKOUT_ACTION_BEFORE_ORDER_UPDATE = 'OnBeforeTinkoffCheckoutOrderUpdate';
const TINKOFF_CHECKOUT_ACTION_ORDER_UPDATE        = 'OnTinkoffCheckoutOrderUpdate';
const TINKOFF_CHECKOUT_ACTION_AFTER_ORDER_UPDATE  = 'OnAfterTinkoffCheckoutOrderUpdate';

const TINKOFF_CHECKOUT_ACTION_BEFORE_ITEM_ADD_TO_INVOICE = 'OnBeforeTinkoffCheckoutItemAddToInvoice';

const TINKOFF_CHECKOUT_ACTION_GET_DELIVERY          = 'OnTinkoffCheckoutGetDelivery';
const TINKOFF_CHECKOUT_ACTION_GET_DELIVERY_PRICE    = 'OnTinkoffCheckoutGetDeliveryPrice';
const TINKOFF_CHECKOUT_ACTION_GET_DELIVERY_VAT_RATE = 'OnTinkoffCheckoutGetDeliveryVatRate';

const TINKOFF_CHECKOUT_ACTION_GET_PRODUCT_WEIGHT = 'OnTinkoffCheckoutGetProductWeight';
const TINKOFF_CHECKOUT_ACTION_GET_PRODUCT_HEIGHT = 'OnTinkoffCheckoutGetProductHeight';
const TINKOFF_CHECKOUT_ACTION_GET_PRODUCT_WIDTH  = 'OnTinkoffCheckoutGetProductWidth';
const TINKOFF_CHECKOUT_ACTION_GET_PRODUCT_LENGTH = 'OnTinkoffCheckoutGetProductLength';

if (!IsModuleInstalled(TINKOFF_CHECKOUT_MODULE_ID)) {
    return;
}


