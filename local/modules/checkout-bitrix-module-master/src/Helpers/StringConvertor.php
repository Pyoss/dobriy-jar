<?php

namespace TinkoffCheckout\Helpers;

class StringConvertor
{
    public static function toFloat($string)
    {
        if (!$string) {
            return 0.0;
        }

        $string = trim($string);
        $string = str_replace(',', '.', $string);

        if (!intval($string) && !floatval($string)) {
            return 0.0;
        }

        try {
            $string = +$string;
        } catch (\Exception $e) {
            $string = floatval($string);
        }
        return $string;
    }
}