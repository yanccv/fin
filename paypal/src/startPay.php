<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

session_start();
require __DIR__.'/../vendor/autoload.php';
$api = new ApiContext(
    new OAuthTokenCredential(
        'AehpPHIPYFb4yRKvxUW8eA5EpJx8-02NYlcwFGj8M95F_pcqVemV4vYGh2MPSxdb7Y_Hov9lEaawqpXi',
        'EKuX6Qewjh1TnqW-Uwx7-YInvS_3-Vo5wMWMlXwq4MEMhr6PGyblYF4NlN2nLWqrZq_fA1IT-6e3HN5g'
    )
);
$api->setConfig([
    'mode' => 'sandbox',
    'http.ConnectionTimeOut' => 30,
    'log.LogEnabled' => false,
    'log.FileName' => '',
    'log.LogLevel' => 'FINE',
    'validation.level' => 'log'
]);
