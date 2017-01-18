<?php

return [

    'driver' => env('MAIL_DRIVER', 'smtp'),

    'host' => env('MAIL_HOST', 'smtp.qq.com'),

    'port' => env('MAIL_PORT', 25),

    'from' => ['address' => '1193297950@qq.com', 'name' => 'czs'],

    'encryption' => env('MAIL_ENCRYPTION', 'ssl'),

    'username' => env('MAIL_USERNAME',''),

    'password' => env('MAIL_PASSWORD',''), //hrozrodrqiiqbaee

    'sendmail' => '/usr/sbin/sendmail -bs',

];
