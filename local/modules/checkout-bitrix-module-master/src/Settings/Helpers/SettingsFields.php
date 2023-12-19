<?php

namespace TinkoffCheckout\Settings\Helpers;

use COption;
use Bitrix\Main\Context;

class SettingsFields
{
    public static function getFieldName($field, $site = null)
    {
        $site = !$site ? self::getCurrentSite() : $site;
        return $field . '_' . $site;
    }

    public static function is($haystack, $needle, $site = null){
        if ($site){
            $needle = self::getFieldName($needle);
        }

        return strpos($haystack, $needle) !== false;
    }

    public static function getFieldValue($field, $site = null)
    {
        require_once __DIR__ . '/../../../include.php';

        $field = self::getFieldName($field, $site);
        return COption::GetOptionString(TINKOFF_CHECKOUT_MODULE_ID, $field, '');
    }

    private static function getCurrentSite()
    {
        return Context::getCurrent()->getSite();
    }
}