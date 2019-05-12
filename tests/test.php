<?php
require_once __DIR__ . '/../vendor/autoload.php';

use NetsSdk\Transaction;
use NetsSdk\Merchant;
use NetsSdk\Request;
use NetsSdk\Currencies;

/* Merchant details. Can be passed to constructor too */
$merchant = new Merchant();
$merchant->setMerchantId("")
         ->setAccessToken("");

/* Create a new request */
$request = new Request();
$request->setOrderNumber(1337)
        ->setAmount(500)
        ->setCurrencyCode(Currencies::NorwegianKrone)
        ->setCustomerFirstName("Nitrus")
        ->setCustomerLastName("Brio")
        ->setCustomerEmail("nitrus.cheezedoodles91@hotmail.com")
        ->setOrderDescription("Equipment for cortex vortex.")
        ->setRedirectUrl("http://localhost/cash4life");

/* Create a new transaction */
$transaction = new Transaction();
$transaction->setMerchant($merchant)
            ->setRequest($request);

/* This might fail - wrap in try/catch */
$transaction->register();
