<?php

namespace TinkoffCheckout\Entities;

class Vat
{

    const VAT_ZERO_PERCENT = 'vat0';
    const VAT_TEN_PERCENT = 'vat10';
    const VAT_TWENTY_PERCENT = 'vat20';

    const RATE_ASSOC = [
        '0.0' => self::VAT_ZERO_PERCENT,
        '0.1' => self::VAT_TEN_PERCENT,
        '0.2' => self::VAT_TWENTY_PERCENT,
    ];

    const RATE_NONE = 'none';

    public static function getVatAssoc($vatValue)
    {
        require_once __DIR__ . '/../../include.php';

        if ($vatValue > 1) {
            $vatValue = $vatValue / 100;
        }
        $vatValue = round($vatValue, 1);
        $vatValue = '' . $vatValue;

        $assoc = self::RATE_ASSOC;
        return isset($assoc[$vatValue]) ? $assoc[$vatValue] : self::RATE_NONE;
    }
}