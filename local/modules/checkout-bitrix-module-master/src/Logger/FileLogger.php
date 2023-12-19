<?php

namespace TinkoffCheckout\Logger;

use Bitrix\Main\Diag\Debug;

class FileLogger extends AbstractLogger
{
    protected $channel = 'default';

    public function log($level, $message, $context = [])
    {
        $message = $this->getDatePrefix() . strtoupper($level) . ': ' . $message;
        $message = $message . "\nContext: " . json_encode($context);
        $message = $message . "\n";

        $file = $this->getChannel() . '.log';
        $dir  = __DIR__ . '/../../storage/log';

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $dir = realpath($dir);

        file_put_contents($dir . '/' . $file, $message, FILE_APPEND);

        Debug::writeToFile($message);
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function setChannel($channel)
    {
        $this->channel = $channel;
    }
}