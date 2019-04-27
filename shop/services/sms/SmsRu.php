<?php

namespace shop\services\sms;

use yii\base\InvalidConfigException;

class SmsRu implements IsmsSender
{
    private $appId;
    private $url;

    public function __construct($appId, $url = 'http://sms.ru/sms/send')
    {
        if (empty($appId)) {
            throw new InvalidConfigException('Sms appId must be set.');
        }

        $this->appId = $appId;
        $this->url = $url;
    }

    public function send($number, $text): void
    {
        $ch = \curl_init($this->url);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        \curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        \curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'api_id' => $this->appId,
            'to' => '+' . trim($number, '+'),
            'text' => $text
        ]);

        \curl_exec($ch);

        if (\curl_errno($ch)) {
            throw new \RuntimeException(
                'Could not send the request: ' . \curl_error($ch)
            );
        }

        $resultStatus = \curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($resultStatus !== 200) {
            throw new \RuntimeException(
                'Request failed. HTTP status code: ' . $resultStatus
            );
        }

        \curl_close($ch);
    }
}
