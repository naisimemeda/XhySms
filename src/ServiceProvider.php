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

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(XhySms::class, function () {
            return new XhySms(config('xhysms.gateways'));
        });

        $this->app->alias(XhySms::class, 'XhySms');
    }

    public function provides()
    {
        return [XhySms::class, 'XhySms'];
    }
}
