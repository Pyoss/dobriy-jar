<?php

IncludeModuleLangFile(__FILE__);

use Bitrix\Main\Diag\Debug;
use Bitrix\Sale\Internals\PaySystemActionTable;
use COption;
use Bitrix\Main\Loader;
use TinkoffCheckout\Entities\BlockProperty;
use TinkoffCheckout\Entities\Delivery;
use TinkoffCheckout\Entities\OrderStatuses;
use TinkoffCheckout\Entities\PaymentMethod;
use Bitrix\Sale\Delivery\Services\Manager as DeliveryManager;


class tinkoff_checkout extends CModule
{
    var $MODULE_ID = 'tinkoff.checkout';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    const PAYMENT_NAME = 'Tinkoff Checkout';

    public function __construct()
    {
        $arModuleVersion = array();

        include(__DIR__ . '/version.php');


        $this->MODULE_VERSION      = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME        = 'Тинькофф Корзина';
        $this->MODULE_DESCRIPTION = 'Модуль интеграции с Тинькофф Корзиной';

        $this->PARTNER_NAME = "Тинькофф";
        $this->PARTNER_URI  = "https://www.tinkoff.ru/";
    }

    function DoInstall()
    {
        $this->InstallFiles();
        $this->InstallEvents();

        $this->InstallDB(false);

        $this->InstallPayment();
        $this->InstallDelivery();
        $this->InstallBlockProperties();
        $this->InstallOrderStatuses();
    }

    function DoUninstall()
    {
        $this->UnInstallFiles();
        $this->UnInstallEvents();

        $this->UnInstallDB(false);

        $this->UnInstallPayment();
        $this->UnInstallDelivery();
        $this->UnInstallBlockProperties();

        return true;
    }

    function InstallFiles()
    {
        if ($_ENV["COMPUTERNAME"] != 'BX') {
            CopyDirFiles(
                $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/components",
                $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components",
                true,
                true
            );
        }

        return true;
    }

    function InstallEvents()
    {
        return true;
    }

    function InstallDB()
    {
        RegisterModule($this->MODULE_ID);

        return true;
    }

    function InstallPayment()
    {
        require_once __DIR__ . '/../include.php';

        $paymentMethod = new PaymentMethod();
        return $paymentMethod->add();
    }

    function InstallDelivery()
    {
        require_once __DIR__ . '/../include.php';

        Delivery::add(Delivery::DELIVERY_TYPE_MERCHANT);
        Delivery::add(Delivery::DELIVERY_TYPE_METASHIP);

        return true;
    }

    function InstallBlockProperties()
    {
        // b_iblock_property
        BlockProperty::add(BlockProperty::TYPE_DECLARED_VALUE);

        return true;
    }

    function InstallOrderStatuses()
    {
        require_once __DIR__ . '/../include.php';

        OrderStatuses::add(OrderStatuses::TYPE_METASHIP_PROCESSING);
        OrderStatuses::add(OrderStatuses::TYPE_METASHIP_PROCESSING_SUCCESS);
        OrderStatuses::add(OrderStatuses::TYPE_METASHIP_PROCESSING_FAILURE);
        OrderStatuses::add(OrderStatuses::TYPE_METASHIP_PROCESSING_NOT_STARTED);
    }

    function UnInstallFiles($arParams = array())
    {
        \Bitrix\Main\IO\Directory::deleteDirectory(
            $_SERVER['DOCUMENT_ROOT'] . "/bitrix/components/tinkoff/checkout.button"
        );

        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function UnInstallDB($arParams = array())
    {
        UnRegisterModule($this->MODULE_ID);

        // TODO: добавить удаление важных полей из настроек

        return true;
    }

    function UnInstallPayment()
    {
        require_once __DIR__ . '/../include.php';

        $paymentMethod = new PaymentMethod();
        return $paymentMethod->remove();
    }

    function UnInstallDelivery()
    {
        require_once __DIR__ . '/../include.php';

        $delivery = new Delivery();
        $delivery::remove(Delivery::DELIVERY_TYPE_MERCHANT);
        $delivery::remove(Delivery::DELIVERY_TYPE_METASHIP);

        return true;
    }

    function UnInstallBlockProperties()
    {
        BlockProperty::remove(BlockProperty::TYPE_DECLARED_VALUE);
    }
}