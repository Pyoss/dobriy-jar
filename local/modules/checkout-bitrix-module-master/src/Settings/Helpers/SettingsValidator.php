<?php

namespace TinkoffCheckout\Settings\Helpers;

use TinkoffCheckout\Helpers\StringConvertor;

class SettingsValidator
{
    public static function float($haystack, $needle, $value, $min, $max)
    {
        if (SettingsFields::is($haystack, $needle) && $value !== '' && !is_null($value)) {
            return self::minMax($value, $min, $max);
        }
        return $value;
    }

    private static function minMax($value, $min, $max)
    {
        $value = StringConvertor::toFloat($value);
        if ($value < $min) {
            $value = $min;
        } elseif ($value > $max) {
            $value = $max;
        }

        return $value;
    }
}