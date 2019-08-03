<h1 align="center"> xhy-sms </h1>

<p align="center"> 基于 <a hrer='https://www.aliyun.com/product/sms?spm=5176.12825654.eofdhaal5.17.3dbd2c4aXWqet5'>Aliyun</a> or <a hrer='https://console.cloud.tencent.com/sms'>Qcloud</a> 的 PHP 短信发送扩展。</p>


## Installing

```shell
$ composer require nice/xhy-sms -vvv
```

## Laravel 中使用
首先在 config 目录中增加 xhysms.php 文件

```shell
touch config/xhysms.php
```

## 配置示例

```php
return [ 
    'gateways' => [  //需要使用那个配置那个
        'aliyun' => [ 
            'access_key_id' => env('ALIYUN_API_KEY'),
            'access_key_secret' => env('ALIYUN_API_KEY_SECRET'),
            'sign_name' => env('SIGN_NAME'),
        ],
        'qcloud' => [
            'sdk_app_id' => '', // SDK APP ID
            'app_key' => '', // APP KEY
            'sign_name' => '',
        ],
    ],
];
```

## 使用

```php
    use Nice\XhySms\XhySms;    
    /*
        扩展包已经自己绑定一个单例到容器中 所以你也可以这样 
        app('XhySms')->send();
    */
    public function show(XhySms $Sms) {  //阿里云 
        $Sms->send('185****4076', [
            'template' => 'SMS_******34', 
            'data' => [
              'code' => 123456   //变量名
            ]
        ],'aliyun');
    }
    
    public function show(XhySms $Sms) {  //腾讯云
            $Sms->send('185****4076', [
                'template' => '6***9', 
                'data' => [
                    123456, 555555
                ]
            ],'qcloud');
        }
        
```

## 其他项目中使用

```php
    $config =  [
        'gateways' => [
            'aliyun' => [ 
                'access_key_id' => '',
                'access_key_secret' => '',
                'sign_name' => '',
            ],
            'qcloud' => [
                'sdk_app_id' => '', // SDK APP ID
                'app_key' => '', // APP KEY
                'sign_name' => '',
            ],
        ],
    ];
    
    $Sms = new XhySms($config);
    
    $Sms->send('185****4076', [
       'template' => 'SMS_******34', 
       'data' => [
          'code' => 123456   
       ]
    ],'aliyun');
    
```
## 文档参考
- [阿里云](https://help.aliyun.com/document_detail/101414.html?spm=a2c4g.11174283.6.616.8a632c42uq6xsD)
- [腾讯云 SMS](https://cloud.tencent.com/document/product/382/5976)
## License

MIT