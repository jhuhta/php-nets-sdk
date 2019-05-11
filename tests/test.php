<?php
require_once __DIR__ . '/../vendor/autoload.php';

use NetsSdk\Transaction;
$transaction = new Transaction();
echo $transaction->test();