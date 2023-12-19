<?php

namespace TinkoffCheckout\TinkoffApi;

use TinkoffCheckout\Logger\FileLogger;
use TinkoffCheckout\Settings\Helpers\SettingsFields;

class Client
{
    const ERROR_FIELD_NAME = 'errorMessage';

    public function createOrder($params = [])
    {
        require_once __DIR__ . '/../../include.php';

        $logger = new FileLogger();
        $logger->setChannel('requests');

        if (!$params) {
            return [self::ERROR_FIELD_NAME => 'Недостаточно данных для создания заказа'];
        }

        $url  = 'https://secured-openapi.business.tinkoff.ru/api/v2/checkout/order';
        $curl = $this->getCURL($url);
        curl_setopt_array($curl, [
            CURLOPT_POSTFIELDS => json_encode($params),
        ]);

        $response = curl_exec($curl);
        $logger->info('create_order', [
            'url'      => $url,
            'query'    => $params,
            'response' => $response
        ]);

        curl_close($curl);

        return json_decode($response)
            ? json_decode($response, true)
            : [self::ERROR_FIELD_NAME => 'Формат ответа сервера некорректный'];
    }

    public function getOrder($orderID)
    {
        require_once __DIR__ . '/../../include.php';

        $logger = new FileLogger();
        $logger->setChannel('requests');

        $shopID = SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_SHOP_ID);

        $url  = "https://secured-openapi.business.tinkoff.ru/api/v2/checkout/order/$shopID/by/$orderID";
        $curl = $this->getCURL($url, 'GET');

        $response = curl_exec($curl);
        if ($response === false) {
            $response = curl_error($curl);
        }

        $logger->info('get_order', [
            'url'      => $url,
            'response' => $response
        ]);

        curl_close($curl);

        return json_decode($response)
            ? json_decode($response, true)
            : [self::ERROR_FIELD_NAME => 'Формат ответа сервера некорректный'];
    }

    protected function getCURL($url = null, $method = 'POST')
    {
        require_once __DIR__ . '/../../include.php';

        $cert  = realpath(SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_MTLS_CERT));
        $key   = realpath(SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_MTLS_PRIVATE));
        $token = SettingsFields::getFieldValue(TINKOFF_CHECKOUT_FIELD_TOKEN);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSLCERT        => $cert,
            CURLOPT_SSLKEY         => $key,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $token
            ],
            CURLOPT_URL            => $url
        ));

        return $curl;
    }
}