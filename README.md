# jd-sdk
京东开放平台sdk

Usage

main.php 配置文件增加jd sdk组件:

```php
<?php

use JDSDK\Foundation\Application;

return [
    'components' => [
        'jd' => function () {
            return new Application([
               'clientId' => 'XXX',
               'clientSecret' => 'XXX',
               'username' => 'XXX',
               'password' => 'XXX'
           ]);
        },
    ],
];
```

调用方式：

```php
//组件化调用

//订单模块：
$order = \Yii::$app->jd->order;
$order->confirmOrder($jdOrderId, $companyPayMoney);

//商品模块：
$goods = \Yii::$app->jd->goods;
$goods->queryDetail($sku, $queryExts);
```

本项目借鉴了easywechat项目的编程思路，在此感谢[overtrue](https://github.com/overtrue)！
