<?php

    namespace NetsSdk;
    
    use NetsSdk\Price;


    class Request {
        
        // Merchant+Token comes from Merchant Object

        protected $_transactionId;

        //https://shop.nets.eu/web/partners/register
        
        /* Public will be available when converting to JSON */
        public $orderNumber;        
        public $customerFirstName;
        public $customerLastName;
        public $customerEmail;
        public $orderDescription;
        public $redirectUrl;
        
        // Set these via price object
        public $amount;
        public $currencyCode;
        
        /* Internal props */
        protected $_price;
        
        
        // need; AMOUNT and CURRENCY CODE as public. Derive from price.
        
        
        /**
         * Transaction ID is a unique ID identifying each transaction within the Merchant ID in Netaxept at any point. 
         * @return String
         */
        public function getTransactionId() {
            return $this->_transactionId;
        }
        
        /**
         * A transaction identifier defined by the merchant.
         * @return String
         */

        public function getOrderNumber() {
            return $this->orderNumber;
        }
        
        /**
         * 
         * @return Price
         */
        public function getPrice(){
            return $this->_price;
        }

        public function getCustomerFirstName() {
            return $this->customerFirstName;
        }

        public function getCustomerLastName() {
            return $this->customerLastName;
        }

        public function getCustomerEmail() {
            return $this->customerEmail;
        }

        public function getOrderDescription() {
            return $this->orderDescription;
        }

        public function getRedirectUrl() {
            return $this->redirectUrl;
        }
        

        

        
        /**
         * Transaction ID is a unique ID identifying each transaction within the Merchant ID in Netaxept at any point. 
         * If Transaction ID is omitted, Netaxept will generate a unique Transaction ID for the transaction.
         * 
         * @param String $transactionId (Max Length is 32)
         * @return $this
         */
        public function setTransactionId($transactionId) {
            $this->_transactionId = $transactionId;
            return $this;
        }

        /**
         * A transaction identifier defined by the merchant. 
         * Nets recommends to generate each transaction a unique order number but if wanted the same order number can be used several times. 
         * Digits and letters are allowed except special characters and scandinavian letters like Æ Ø Å Ä Ö.
         * 
         * @param type $orderNumber
         * @return $this
         */
        public function setOrderNumber($orderNumber) {
            $this->orderNumber = $orderNumber;
            return $this;
        }
        
        /**
         * Sets the price via a price object.
         * Also sets amount and currencyCode property used when creating transaction.
         * @param Price $price
         * @return $this
         */
        public function setPrice(Price $price){
            $this->_price = $price;
            $this->amount = $price->getStrippedDecimalInteger();
            $this->currencyCode = $price->getCurrency();
            return $this;
        }

        
        /**
         * Customer's first name.
         * 
         * @param String $customerFirstName (Max Length: 64)
         * @return $this
         */
        public function setCustomerFirstName($customerFirstName) {
            $this->customerFirstName = $customerFirstName;
            return $this;
        }
        
        /**
         * Customer's last name.
         * 
         * @param type $customerLastName (Max Length: 64)
         * @return $this
         */
        public function setCustomerLastName($customerLastName) {
            $this->customerLastName = $customerLastName;
            return $this;
        }
        
        /**
         * The customer's email address.
         * 
         * @param String $customerEmail (Max Length: 128)
         * @return $this
         */
        public function setCustomerEmail($customerEmail) {
            $this->customerEmail = $customerEmail;
            return $this;
        }
        
        /**
         * Free-format textual description determined by the merchant for the transaction. 
         * This can be HTML-formatted. If you are using Netaxept hosted payment window, this description will appear in the payment window for the customer. 
         * Unlike the other fields, the order description will not cause the call to fail if it exceeds its maximum length, rather the field will be truncated to its maximum length.
         * @param String $orderDescription (Max Length: 1500)
         * @return $this
         */
        public function setOrderDescription($orderDescription) {
            $this->orderDescription = $orderDescription;
            return $this;
        }
        
        /**
         * Indicates where to send the customer when the transaction after the Register call and Terminal phase. 
         * This URL can contain GET parameters.
         * The redirect URL is optional when using "AutoAuth", and shouldn't be used with Call centre transactions.
         * 
         * @param String $redirectUrl (Max Length: 256)
         * @return $this
         */
        public function setRedirectUrl($redirectUrl) {
            $this->redirectUrl = $redirectUrl;
            return $this;
        }
        
        
        public function asArray(){
            return json_decode(json_encode($this), true);
        }



        
    }
