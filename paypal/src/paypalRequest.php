<?php

use PayPal\Api\Payer;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\FundingInstrument;
use PayPal\Api\PaymentCard;

require 'startPay.php';
include("../../includes/classdb.php");
/*
$card = new PaymentCard();
$card->setType("visa")
    ->setNumber("4669424246660779")
    ->setExpireMonth("11")
    ->setExpireYear("2019")
    ->setCvv2("012")
    ->setFirstName("Jo  e")
    ->setBillingCountry("US")
    ->setLastName("Shopper");

$fi = new FundingInstrument();
$fi->setPaymentCard($card);
*/

$payer = new Payer();
$payer->setPaymentMethod("paypal");


$bd = new dbMysql();
$bd->dbConectar();

$items = array();
switch ($_GET['idform']) {
    case 'Activar':
        $id = (string) $_GET['id'];
        $ConInvita=$bd->dbConsultar("select c.minimoap monto from clientes as c inner join paises as p on c.pais=p.id inner join monedas as m on p.monedaoficial=m.id where c.cedula=? limit 1", array($id));
        if ($bd->Error) {
            echo $bd->MsgError;
            exit();
        }

        $RowInvita = $ConInvita->fetch_object();

        $item1 = new Item();
        $item1->setName('Activacion en la Franquicia de Participacion')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setSku("000001") // Similar to `item_number` in Classic API
            ->setPrice($RowInvita->monto);
        array_push($items, $item1);
        break;
    default:
        die('Acceso Invalido');
        break;
}

$itemList = new ItemList();
$itemList->setItems($items);

$details = new Details();
$details->setShipping(0)
    ->setTax(0)
    ->setSubtotal($RowInvita->monto);

$amount = new Amount();
$amount->setCurrency("USD")
    ->setTotal($RowInvita->monto)
    ->setDetails($details);

$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription("Pago de Activacion ")
    ->setInvoiceNumber(uniqid());

//$baseUrl = getBaseUrl();
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl('http://'.$_SERVER['SERVER_NAME']."/paginas/paypalResult.php?aproved=true&id={$id}&idform={$_GET['idform']}")
    ->setCancelUrl('http://'.$_SERVER['SERVER_NAME']."paginas/paypalResult.php?aproved=false&id={$id}&idform={$_GET['idform']}");

$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setTransactions([$transaction])
    ->setRedirectUrls($redirectUrls);

try {
    $payment->create($api);
    //Gurdar datos de la peticion
} catch (PPConnectionException $e) {
    //Se Genero un Error
    header('http://'.$_SERVER['SERVER_NAME'].'/paginas/paypalResult.php?error=1');
}
//echo '<pre>';
foreach ($payment->getLinks() as $key => $link) {
    if ($link->getRel() == 'approval_url') {
        $redirectUrl = $link->getHref();
    }
}
//header('Location: '.$redirectUrl);
$approvalUrl = $payment->getApprovalLink();
header('location: '.$approvalUrl);
