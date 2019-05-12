<?php

    namespace NetsSdk;
    
    use NetsSdk\Price;


    class Request {
        
        // Merchant+Token comes from Merchant Object

        protected $_transactionId;

        //https://shop.nets.eu/web/partners/register

        protected $_orderNumber;        
        protected $_price;
        protected $_customerFirstName;
        protected $_customerLastName;
        protected $_customerEmail;
        protected $_orderDescription;
        protected $_redirectUrl;
        protected $_isTestEnvironment;
        
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
            return $this->_orderNumber;
        }
        
        /**
         * 
         * @return Price
         */
        public function getPrice(){
            return $this->_price;
        }

        public function getCustomerFirstName() {
            return $this->_customerFirstName;
        }

        public function getCustomerLastName() {
            return $this->_customerLastName;
        }

        public function getCustomerEmail() {
            return $this->_customerEmail;
        }

        public function getOrderDescription() {
            return $this->_orderDescription;
        }

        public function getRedirectUrl() {
            return $this->_redirectUrl;
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
            $this->_orderNumber = $orderNumber;
            return $this;
        }
        
        /**
         * Sets the price via a price object.
         * @param Price $price
         * @return $this
         */
        public function setPrice(Price $price){
            $this->_price = $price;
            return $this;
        }

        
        /**
         * Customer's first name.
         * 
         * @param String $customerFirstName (Max Length: 64)
         * @return $this
         */
        public function setCustomerFirstName($customerFirstName) {
            $this->_customerFirstName = $customerFirstName;
            return $this;
        }
        
        /**
         * Customer's last name.
         * 
         * @param type $customerLastName (Max Length: 64)
         * @return $this
         */
        public function setCustomerLastName($customerLastName) {
            $this->_customerLastName = $customerLastName;
            return $this;
        }
        
        /**
         * The customer's email address.
         * 
         * @param String $customerEmail (Max Length: 128)
         * @return $this
         */
        public function setCustomerEmail($customerEmail) {
            $this->_customerEmail = $customerEmail;
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
            $this->_orderDescription = $orderDescription;
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
            $this->_redirectUrl = $redirectUrl;
            return $this;
        }
        
        public function setIsTestEnvironment($boolean){
            /* Strips away any data - we just want good ol' bool */
            $this->_isTestEnvironment = $boolean ? true : false;
        }



        
    }
