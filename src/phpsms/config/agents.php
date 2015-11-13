<?php

/*
 * agents` config
 */
return [

    /*
     * -----------------------------------
     * YunPian
     * 云片代理器
     * -----------------------------------
     * website:http://www.yunpian.com
     * support content sms.
     */
    'YunPian' => [
        //用户唯一标识，必须
        'apikey' => 'your api key',
    ],

    /*
     * -----------------------------------
     * YunTongXun
     * 云通讯代理器
     * -----------------------------------
     * website：http://www.yuntongxun.com/
     * support template sms.
     */
    'YunTongXun' => [
        //主帐号,对应开官网发者主账号下的 ACCOUNT SID
        'accountSid' => 'your account sid',

        //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
        'accountToken' => 'your account token',

        //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
        //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
        'appId' => 'your app id',

        //请求地址
        //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
        //生产环境（用户应用上线使用）：app.cloopen.com
        'serverIP' => 'app.cloopen.com',

        //请求端口，生产环境和沙盒环境一致
        'serverPort' => '8883',

        //REST版本号，在官网文档REST介绍中获得。
        'softVersion' => '2013-12-26',
    ],

    /*
     * -----------------------------------
     * SubMail
     * -----------------------------------
     * website:http://submail.cn/
     * support template sms.
     */
    'SubMail' => [

        'appid' => 'your app id',

        'signature' => 'your app key',
    ],

    /*
     * -----------------------------------
     * luosimao
     * -----------------------------------
     * website:http://luosimao.com
     * support content sms.
     */
    'Luosimao' => [
        // 短信 API key
        // 在管理中心->短信->触发发送下查看
        'apikey' => 'your api key',

        // 语言验证 API key
        // 在管理中心->语音->语音验证下查看
        'voiceApikey' =>  'your voice api key',
    ],

    /*
     * -----------------------------------
     * ucpaas
     * -----------------------------------
     * website:http://ucpaas.com
     * support content sms.
     */
    'Ucpaas' => [
        //主帐号,对应开官网发者主账号下的 ACCOUNT SID
        'accountSid' => 'your account sid',

        //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
        'accountToken' => 'your account token',

        //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
        //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
        'appId' => 'your app id',
    ],
];
