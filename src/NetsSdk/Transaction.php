<?php

    namespace NetsSdk;
    
    use NetsSdk\Merchant;
    use NetsSdk\Request;
    use GuzzleHttp\Client;

    class Transaction {
        
        protected $_merchant;
        protected $_request;
        
        /**
         * Creates a new transaction.
         * You can optionally pass merchant and request to the constructor.
         * 
         * @param Merchant $merchant
         * @param Request $request
         * @throws Exception
         */
        public function __construct($merchant = false, $request = false){
            if($merchant !== false){
                if(!$merchant instanceof Merchant){
                    throw new Exception("Expected either false or a Merchant object");
                }
                else {
                    $this->setMerchant($merchant);
                }
            }
            
            if($request !== false){
                if(!$request instanceof Request){
                    throw new Exception("Expected either false or a Request object");
                }
                else {
                    $this->setRequest($request);
                }
            }
            
            
        }
        
        /**
         * Returns the merchant object.
         * @return Merchant
         */
        public function getMerchant() {
            return $this->_merchant;
        }

        /**
         * Returns request object.
         * @return Request
         */
        public function getRequest() {
            return $this->_request;
        }

        /**
         * Sets the merchant object.
         * @param Merchant $merchant
         * @return $this
         */
        public function setMerchant(Merchant $merchant) {
            $this->_merchant = $merchant;
            return $this;
        }

        /**
         * Sets the request object.
         * @param Request $request
         * @return $this
         */
        public function setRequest(Request $request) {
            $this->_request = $request;
            return $this;
        }
        
        /**
         * Registers the request and returns object with generated/specified ID (if successful). 
         * 
         * @param type $request
         * @return Request
         * @throws Exception
         */
        public function register($request = false){
            if(!$request){ $request = $this->getRequest(); }
            if(!$request instanceof Request){
                throw new Exception("Expected a valid request object");
            }
            
            $client = new Client([
                'base_uri' => 'https://api.github.com',
                'timeout'  => 2.0
            ]);
            
            $response = $client->request('GET', 'user');
            
            var_dump($response);
            
            // Perform request return object with new id.
            
            return $request;
        }



    }
?>