<?php

/*
 * This file is part of the niceyo/xhy-sms.
 *
 * (c) nice<i@2514430140@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Nice\XhySms\Gateways;

use GuzzleHttp\Client;
use Nice\XhySms\Exceptions\GatewayErrorException;
use Nice\XhySms\Traits\HasHttpRequest;

/**
 * Class AliyunGateway.
 *
 * @author carson <docxcn@gmail.com>
 *
 * @see https://help.aliyun.com/document_detail/55451.html
 */
class AliyunGateway
{
    use HasHttpRequest;

    const ENDPOINT_URL = 'http://dysmsapi.aliyuncs.com';

    const ENDPOINT_METHOD = 'SendSms';

    const ENDPOINT_VERSION = '2017-05-25';

    const ENDPOINT_FORMAT = 'JSON';

    const ENDPOINT_REGION_ID = 'cn-hangzhou';

    const ENDPOINT_SIGNATURE_METHOD = 'HMAC-SHA1';

    const ENDPOINT_SIGNATURE_VERSION = '1.0';

    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function getHttpClient(array $options = [])
    {
        return new Client($options);
    }

    public function send($to, $message)
    {
        $params = [
            'RegionId'         => self::ENDPOINT_REGION_ID,
            'AccessKeyId'      => $this->config['access_key_id'],
            'Format'           => self::ENDPOINT_FORMAT,
            'SignatureMethod'  => self::ENDPOINT_SIGNATURE_METHOD,
            'SignatureVersion' => self::ENDPOINT_SIGNATURE_VERSION,
            'SignatureNonce'   => uniqid(),
            'Timestamp'        => gmdate('Y-m-d\TH:i:s\Z'),
            'Action'           => self::ENDPOINT_METHOD,
            'Version'          => self::ENDPOINT_VERSION,
            'PhoneNumbers'     => !\is_null($to) ? strval($to) : $to,
            'SignName'         => $this->config['sign_name'],
            'TemplateCode'     => $message['template'],
            'TemplateParam'    => json_encode($message['data'], JSON_FORCE_OBJECT),
        ];
        $params['Signature'] = $this->generateSign($params);

        $result = $this->get(self::ENDPOINT_URL, $params);

        if ('OK' != $result['Code']) {
            throw new GatewayErrorException($result['Message'], $result['Code'], $result);
        }

        return $result;
    }

    /**
     * Generate Sign.
     *
     * @param array $params
     *
     * @return string
     */
    protected function generateSign($params)
    {
        ksort($params);
        $accessKeySecret = $this->config['access_key_secret'];
        $stringToSign = 'GET&%2F&'.urlencode(http_build_query($params, null, '&', PHP_QUERY_RFC3986));

        return base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret.'&', true));
    }

    protected function getBaseOptions()
    {
        $options = [
            'base_uri' => method_exists($this, 'getBaseUri') ? $this->getBaseUri() : '',
            'timeout'  => method_exists($this, 'getTimeout') ? $this->getTimeout() : 5.0,
        ];

        return $options;
    }
}
