<?php
require_once __DIR__ . '/../vendor/autoload.php';

use NetsSdk\Transaction;
use NetsSdk\Merchant;
use NetsSdk\Request;
use NetsSdk\Currencies;
use NetsSdk\Price;




/* Merchant details. Can be passed to constructor too */
$merchant = new Merchant();
$merchant->setMerchantId("")
         ->setAccessToken("");

/* Create a new price of 13,37 NOK */
$price = new Price(13.37, Currencies::NorwegianKrone);

/* Create a new request */
$request = new Request();
$request->setOrderNumber(1337)
        ->setPrice($price)
        ->setCustomerFirstName("Nitrus")
        ->setCustomerLastName("Brio")
        ->setCustomerEmail("nitrus.cheezedoodles91@hotmail.com")
        ->setOrderDescription("Equipment for cortex vortex.")
        ->setRedirectUrl("http://localhost/cash4life")
        ->setIsTestEnvironment(true);

/* Create a new transaction */
$transaction = new Transaction();
$transaction->setMerchant($merchant)
            ->setRequest($request);

/* This might fail - wrap in try/catch */
$transaction->register();
