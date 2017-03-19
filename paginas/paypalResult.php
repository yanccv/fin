<?php

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

$starpay='../paypal/src/startPay.php';
include("../includes/classdb.php");
if (file_exists($starpay)) {
    require $starpay;
}


if (isset($_GET['aproved']) && $_GET['aproved'] == 'true') {
    $bd = new dbMysql();
    $bd->dbConectar();

    $paymentId = $_GET['paymentId'];
    $payment = Payment::get($paymentId, $api);

    $execution = new PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);

    $transaction = new Transaction();
    $amount = new Amount();
    $details = new Details();


    switch ($_GET['idform']) {
        case 'Activar':
            $id = (string) $_GET['id'];
            $ConInvita=$bd->dbConsultar("select c.minimoap monto from clientes as c inner join paises as p on c.pais=p.id inner join monedas as m on p.monedaoficial=m.id where c.cedula=? limit 1", array($id));
            if ($bd->Error) {
                echo $bd->MsgError;
                exit();
            }

            $RowInvita = $ConInvita->fetch_object();

            $details->setShipping(0)
                ->setTax(0)
                ->setSubtotal($RowInvita->monto);
            $amount->setTotal($RowInvita->monto);
            break;
        default:
            die('Acceso Invalido');
            break;
    }

    $amount->setCurrency('USD');
    $amount->setDetails($details);
    $transaction->setAmount($amount);

    $execution->addTransaction($transaction);
    try {
        $result = $payment->execute($execution, $api);
        try {
            $payment = Payment::get($paymentId, $api);
        } catch (Exception $ex) {
            echo "Get Payment 1";
            print_r($ex);
            ResultPrinter::printError("Get Payment", "Payment", null, null, $ex);
            exit(1);
        }
    } catch (Exception $ex) {
        echo "Executed Payment Payment 2";
        print_r($ex);
        ResultPrinter::printError("Executed Payment", "Payment", null, null, $ex);
        exit(1);
    }
    echo ("Pago Procesado id:[" . $payment->getId() .'] payment: [' .$payment. ']');
    //return $payment;
} else {
    echo 'Usuario Cancelo el Pago';
    exit;
}
