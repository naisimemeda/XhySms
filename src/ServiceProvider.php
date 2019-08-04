<?php

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
