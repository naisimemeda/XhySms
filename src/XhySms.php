<?php

/*
 * This file is part of the niceyo/xhy-sms.
 *
 * (c) nice<i@2514430140@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Nice\XhySms;

use Nice\XhySms\Gateways\AliyunGateway;
use Nice\XhySms\Gateways\QcloudGateway;

class XhySms
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function send(int $to, array $message, string $gateways)
    {
        switch ($gateways) {
            case 'aliyun':
                $this->SendAliyunSms($to, $message, $gateways);

                break;
            case 'qcloud':
                $this->SendQcloudSms($to, $message, $gateways);

                break;
        }
    }

    public function SendAliyunSms($to, $message, $gateway)
    {
        $Sms = new AliyunGateway($this->config[$gateway]);
        $Sms->send($to, $message);
    }

    public function SendQcloudSms($to, $message, $gateway)
    {
        $Sms = new QcloudGateway($this->config[$gateway]);
        $Sms->send($to, $message);
    }
}
