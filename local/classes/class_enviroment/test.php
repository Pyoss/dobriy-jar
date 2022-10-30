<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('BX_NO_ACCELERATOR_RESET', true);
define('CHK_EVENT', true);
define('BX_WITH_ON_AFTER_EPILOG', true);
define('ANALYTICS_FILENAME', $_SERVER["DOCUMENT_ROOT"]."/bitrix/local/anal_log.txt");
@set_time_limit(0);
@ignore_user_abort(true);

define("BX_CRONTAB_SUPPORT", true);
define("BX_CRONTAB", true);

if(CModule::IncludeModule('sender'))
{
    \Bitrix\Sender\MailingManager::checkPeriod(false);
    \Bitrix\Sender\MailingManager::checkSend();
}

require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/tools/backup.php");
CMain::FinalActions();
?>