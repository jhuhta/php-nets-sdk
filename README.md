# Unoffical PHP SDK for Nets 💸

This is a super unoffical (but offically awesome) SDK that makes payments easy as stealing candy from a baby. And let's be honest, we are all just kids that wants candy 🍭

This plan is to launch this as a composer package, so stay tuned for that to happen 🚚

Something broken, or not working as expected? Feel free to contribute and submit a PR 🥰


## Getting started

### Creating a new transaction
Before you can capture some precious dough, you need to create and register a transaction. We won't cover error handling in this example, but you should wrap this code in a try/catch block (there is a lot of things that can go wrong).

```php
use NetsSdk\Merchant;
use NetsSdk\Currencies;
use NetsSdk\Price;
use NetsSdk\Request;
use NetsSdk\Transaction;

/* Create a new merchant object with your credentials. */
$merchant = new Merchant();
$merchant->setMerchantId("")
         ->setAccessToken("");


/* Create a new price object with your desired currency. */
$price = new Price();
$price->setAmount(13.37)
      ->setCurrency(Currencies::NorwegianKrone)

/* Create new request object */
$request = new Request();
$request->setOrderNumber(1337)
        ->setPrice($price)
        ->setCustomerFirstName("Nitrus")
        ->setCustomerLastName("Brio")
        ->setCustomerEmail("nitrus.cheezedoodles91@hotmail.com")
        ->setOrderDescription("Equipment for cortex vortex.")
        ->setRedirectUrl("http://localhost/cash4life");

/* Create and register new transaction */
$transaction = new Transaction();
$transaction->setMerchant($merchant) 
            ->setRequest($requestObj)
            ->setIsTestEnvironment(true) // Uses Nets test enviroment.
            ->register(); // Posts to Nets API.
    
/* Retrives the transaction ID */
$transactionId = $transaction->getTransactionId();

/* URL to terminal */
$terminalUrl = $transaction->getTerminalUrl();


```

### Authorizing and capturing the payment
The most exiting part. In the previous step we just purchased the tickets; now it's time to grab some cash (or at the very least some bits and bytes). 
We'll assume you "lost" the object from the previous block, and simply query it from the API when we need it.

Again, you should wrap this in try/catch. We double check if the transaction is authorized and/or captured to avoid spamming the API which in turn logs this attempts. 

```php
use NetsSdk\Merchant;
use NetsSdk\Transaction;

/* Passing to the constructor, just to make it less readable */
$merchant = new Merchant("merchantId", "accessToken");

/* We don't need a request this time */
$request = false;

/* Still testing, aren't we? */
$isTestEnvironment = true;

/* Creating a new transaction with a known ID */
$transaction = new Transaction($merchant, false, $transactionId, $isTestEnvironment);
$isAuthorized = $transaction->isAuthorized();

/* Authorize transaction if it hasn't been done already */
if(!$transaction->isAuthorized()){
    $transaction->authorize();
}

/* If nothing is captured - we'll capture too */
if($transaction->getCapturedAmount() < 1){
    $transaction->capture();
}

/* Fetch the full transaction object */
$transaction = $transaction->query();

```

## Useful resources
https://www.youtube.com/watch?v=ewRjZoRtu0Y&t=56

https://blog.jgrossi.com/2013/creating-your-first-composer-packagist-package/