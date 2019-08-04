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

use Nice\XhySms\Exceptions\GatewayErrorException;
use Nice\XhySms\Traits\HasHttpRequest;

/**
 * Class QcloudGateway.
 *
 * @see https://cloud.tencent.com/document/product/382/13297
 */
class QcloudGateway
{
    use HasHttpRequest;

    const ENDPOINT_URL = 'https://yun.tim.qq.com/v5/';

    const ENDPOINT_METHOD = 'tlssmssvr/sendsms';

    const ENDPOINT_VERSION = 'v5';

    const ENDPOINT_FORMAT = 'json';

    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function send($to, $message)
    {
        $signName = $this->config['sign_name'];
        $params = [
            'tel' => [
                'nationcode' => 86,
                'mobile'     => $to,
            ],
            'time'   => time(),
            'extend' => '',
            'ext'    => '',
            'params' => $message['data'],
            'tpl_id' => $message['template'],
        ];
        $random = substr(uniqid(), -10);

        $params['sig'] = $this->generateSign($params, $random);

        $url = self::ENDPOINT_URL.self::ENDPOINT_METHOD.'?sdkappid='.$this->config['sdk_app_id'].'&random='.$random;

        $result = $this->request('post', $url, [
            'headers' => ['Accept' => 'application/json'],
            'json'    => $params,
        ]);

        if (0 != $result['result']) {
            throw new GatewayErrorException($result['errmsg'], $result['result'], $result);
        }

        return $result;
    }

    /**
     * Generate Sign.
     *
     * @param array  $params
     * @param string $random
     *
     * @return string
     */
    protected function generateSign($params, $random)
    {
        ksort($params);

        return hash('sha256', sprintf(
            'appkey=%s&random=%s&time=%s&mobile=%s',
            $this->config['app_key'],
            $random,
            $params['time'],
            $params['tel']['mobile']
        ), false);
    }
}
