<?php

namespace TinkoffCheckout\Entities;

use Bitrix\Sale\OrderStatus;
use Bitrix\Main\Loader;

class OrderStatuses
{
    const TYPE_METASHIP_PROCESSING = 'metaship_processing';
    const TYPE_METASHIP_PROCESSING_SUCCESS = 'metaship_processing_success';
    const TYPE_METASHIP_PROCESSING_FAILURE = 'metaship_processing_failure';
    const TYPE_METASHIP_PROCESSING_NOT_STARTED = 'metaship_processing_not_started';

    const ID_METASHIP_PROCESSING = 'TD';
    const ID_METASHIP_PROCESSING_SUCCESS = 'TS';
    const ID_METASHIP_PROCESSING_FAILURE = 'TF';
    const ID_METASHIP_PROCESSING_NOT_STARTED = 'TN';

    public static function getID($type)
    {
        switch ($type) {
            case self::TYPE_METASHIP_PROCESSING:
                return self::ID_METASHIP_PROCESSING;
            case self::TYPE_METASHIP_PROCESSING_SUCCESS:
                return self::ID_METASHIP_PROCESSING_SUCCESS;
            case self::TYPE_METASHIP_PROCESSING_FAILURE:
                return self::ID_METASHIP_PROCESSING_FAILURE;
            case self::TYPE_METASHIP_PROCESSING_NOT_STARTED:
                return self::ID_METASHIP_PROCESSING_NOT_STARTED;
        }

        return null;
    }

    public static function add($type)
    {
        require_once __DIR__ . '/../../include.php';

        // Подключение зависимостей
        Loader::includeModule("sale");
        Loader::includeModule("catalog");

        $id          = self::getID($type);
        $name        = self::getName($type);
        $description = self::getDescription($type);

        OrderStatus::install([
            'ID'     => $id,
            'TYPE'   => 0,
            'NOTIFY' => 'Y',
            'LANG'   => [
                [
                    'LID'         => 'ru',
                    'STATUS_ID'   => $id,
                    'NAME'        => $name,
                    'DESCRIPTION' => $description
                ],
                [
                    'LID'         => 'en',
                    'STATUS_ID'   => $id,
                    'NAME'        => $name,
                    'DESCRIPTION' => $description
                ]
            ]
        ]);
    }

    private static function getName($type)
    {
        switch ($type) {
            case self::TYPE_METASHIP_PROCESSING:
                return 'Metaship. Обработка заявки';
            case self::TYPE_METASHIP_PROCESSING_SUCCESS:
                return 'Metaship. Ожидает отправки на склад';
            case self::TYPE_METASHIP_PROCESSING_FAILURE:
                return 'Metaship. Ошибка обработки заявки';
            case self::TYPE_METASHIP_PROCESSING_NOT_STARTED:
                return 'Metaship. Заявка не создалась';
        }
        return '';
    }

    private static function getDescription($type)
    {
        switch ($type) {
            case self::TYPE_METASHIP_PROCESSING:
                return 'В Metaship создана заявка на доставку. Заявке присвоен номер (deliveryID). Статус появляется, только если магазин интегрирован с Metaship.';
            case self::TYPE_METASHIP_PROCESSING_SUCCESS:
                return 'Metaship создал доставку в службе доставки, у доставки появился трек‑номер. Можно отправлять заказ на склад службы доставки. Статус появляется, только если магазин интегрирован с Metaship.';
            case self::TYPE_METASHIP_PROCESSING_FAILURE:
                return 'В процессе обработки заявки на доставку в Metaship возникли ошибки. Перейдите в личный кабинет Metaship, чтобы исправить ошибки, или пересоздайте заказ вручную. Может понадобиться дополнительная информация от покупателя. Статус появляется, только если магазин интегрирован с Metaship.';
            case self::TYPE_METASHIP_PROCESSING_NOT_STARTED:
                return 'В процессе обработки заявки на доставку в Metaship возникли ошибки. Перейдите в личный кабинет Metaship, чтобы исправить ошибки, или пересоздайте заказ вручную. Может понадобиться дополнительная информация от покупателя. Статус появляется, только если магазин интегрирован с Metaship.';
        }
        return '';
    }
}