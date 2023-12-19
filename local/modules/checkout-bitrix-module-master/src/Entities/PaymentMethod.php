<?php

namespace TinkoffCheckout\Entities;

use Bitrix\Main\Diag\Debug;
use Bitrix\Sale\Internals\PaySystemActionTable;
use COption;
use Bitrix\Main\Loader;
use TinkoffCheckout\Logger\FileLogger;

class PaymentMethod
{
    const PAYMENT_NAME = 'Тинькофф Корзина';

    public function getID()
    {
        require_once __DIR__ . '/../../include.php';

        // Подключение зависимостей
        Loader::includeModule("sale");

        // Таблица b_sale_pay_system_action
        $result = PaySystemActionTable::getList([
            'filter' => [
                '%NAME' => self::PAYMENT_NAME
            ]
        ]);
        $result = $result->fetch();

        return isset($result['ID']) ? $result['ID'] : null;
    }

    public function add()
    {
        global $APPLICATION;

        // Подключение зависимостей
        Loader::includeModule("sale");

        $paymentID = $this->getID();
        if ($paymentID) {
            return true;
        }

        $paysystem = PaySystemActionTable::add(array(
            "CURRENCY"    => "RUB",
            "NAME"        => self::PAYMENT_NAME,
            "ACTIVE"      => "Y",
            "SORT"        => 100,
            "DESCRIPTION" => "Оплата через сервис Тинькофф Корзина",
        ));
        $paymentID = $paysystem->getId();
        if ($paymentID) {
            $fields = array(
                'PARAMS'               => serialize(array('BX_PAY_SYSTEM_ID' => $paymentID)),
                'ENTITY_REGISTRY_TYPE' => 'ORDER',
                'PAY_SYSTEM_ID'        => $paymentID,
                'LOGOTIP'              => 930,
                'IS_CASH'              => 'Y',
                'ACTION_FILE'          => 'cash',
                'HAVE_PAYMENT'         => 'Y',
                'HAVE_ACTION'          => 'N',
                'HAVE_RESULT'          => 'N',
                'HAVE_PREPAY'          => 'N',
                'HAVE_PRICE'           => 'N',
                'HAVE_RESULT_RECEIVE'  => 'N',
                'ALLOW_EDIT_PAYMENT'   => 'Y',
                'AUTO_CHANGE_1C'       => 'N',
                'CAN_PRINT_CHECK'      => 'N',
                'PSA_NAME'             => GetMessage("SALE_WIZARD_PAYSYSTEM_CASH_NAME"),
            );
            $result = PaySystemActionTable::update($paymentID, $fields);

            $logger = new FileLogger();
            if (is_object($result)) {
                $logger->info('Метод оплаты "' . self::PAYMENT_NAME . '" был успешно добавлен');
            } else {
                $logger->info('Метод оплаты "' . self::PAYMENT_NAME . '" не был добавлен');
                $logger->info($APPLICATION->LAST_ERROR);
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    public function remove()
    {
        // Подключение зависимостей
        Loader::includeModule("sale");

        $paymentID = $this->getID();
        if (!$paymentID) {
            return false;
        }

        // Таблица b_sale_pay_system_action
        PaySystemActionTable::delete($paymentID);

        return true;
    }
}