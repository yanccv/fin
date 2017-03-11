<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

session_start();
require __DIR__.'/../vendor/autoload.php';
$api = new ApiContext(
    new OAuthTokenCredential(
        'AbMrLTCoLBNaix4ZlIBCORevorkOAIP9qKFeavKCpw89VICx30hjYrCgzPD2xq4sqOj8qhF0OtnSRGbs',
        'EJ7biRgfjswWghdNiFzMdZL9kw7m_XQ-1O5Sbv9uKbADwE4OjnSQWahZLte4wdFbHa-FLCWM1j_HLAhd'
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
