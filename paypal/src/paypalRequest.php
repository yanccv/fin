<?php

use PayPal\Api\Payer;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;

require 'startPay.php';
/*
require 'startPay.php';

$payer = new Payer();
$details = new Details();
$amount = new Amount();
$transaction = new Transaction();
$payment = new Payment();
$redirectUrls = new RedirectUrls();

//Payer
$payer->setPaymentMethod('paypal');

//Details
$details->setShipping('2.00')
    ->setTax('0.00')
    ->setSubTotal('22.00');

//Amount
$amount->setCurrency('USD')
    ->setTotal('22.00')
    ->setDetails($details);

$transaction->setAmount($amount)
    ->setDescription('Afiliacion a la Franquicia');

$payment->setIntent('sale')
    ->setPayer($payer)
    ->setTransactions([$transaction]);

$redirectUrls->setReturnUrl('http://finweb/paginas/paypalResult.php')
    ->setCancelUrl('http://finweb/paginas/paypalResult.php');

$payment->setRedirectUrls($redirectUrls);

try {
    $payment->create($api);
    //Gurdar datos de la peticion
} catch (PPConnectionException $e) {
    //Se Genero un Error
    header('http://finweb/paginas/paypalResult.php?error=1');
}

foreach ($payment->getLinks as $link) {
    if ($link->getRel() == 'aproval_url') {
        $redirecUrl = $link->getHref();
    }
}

header('location: '.$redirecUrl);
*/

$payer = new Payer();
$payer->setPaymentMethod("paypal");

$item1 = new Item();
$item1->setName('Ground Coffee 40 oz')
    ->setCurrency('USD')
    ->setQuantity(1)
    ->setSku("123123") // Similar to `item_number` in Classic API
    ->setPrice(7.5);
$item2 = new Item();
$item2->setName('Granola bars')
    ->setCurrency('USD')
    ->setQuantity(5)
    ->setSku("321321") // Similar to `item_number` in Classic API
    ->setPrice(2);

$itemList = new ItemList();
$itemList->setItems(array($item1, $item2));

$details = new Details();
$details->setShipping(1.2)
    ->setTax(1.3)
    ->setSubtotal(17.50);

$amount = new Amount();
$amount->setCurrency("USD")
    ->setTotal(20)
    ->setDetails($details);

$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription("Payment description")
    ->setInvoiceNumber(uniqid());

//$baseUrl = getBaseUrl();
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl('http://finweb/paginas/paypalResult.php')
    ->setCancelUrl('http://finweb/paginas/paypalResult.php');

$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions(array($transaction));

try {
    $payment->create($api);
    //Gurdar datos de la peticion
} catch (PPConnectionException $e) {
    //Se Genero un Error
    header('http://finweb/paginas/paypalResult.php?error=1');
}

$approvalUrl = $payment->getApprovalLink();
header('location: '.$approvalUrl);
