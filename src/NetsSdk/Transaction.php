<?php

    namespace NetsSdk;
    
    use NetsSdk\Merchant;
    use NetsSdk\Request;
    use NetsSdk\NetsException;
    use GuzzleHttp\Client;

    class Transaction {
        
        public $transactionId;
        
        protected $_merchant;
        protected $_request;
        protected $_client;
        
        const ENDPOINT_URL_PROD = 'https://epayment.nets.eu';
        const ENDPOINT_URL_TEST = 'https://test.epayment.nets.eu';
        
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
        
        public function getTransactionId(){
            return $this->transactionId;
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
        public function register(){
            $response = $this->_performRequest('Register', $this->getRequest()->asArray());
            $this->transactionId = $response->TransactionId->__toString();
            return $this->_transactionId;
        }
        
        public function authorize(){
           return $this->_runOperation('AUTH');
        }
        
        public function capture(){
            return $this->_runOperation("CAPTURE");
        }
        
        public function query(){
            return $this->_runOperation("QUERY");
        }
        
        public function getTerminalUrl(){
            $merchantId = $this->getMerchant()->getMerchantId();
            $transactionId = $this->getTransactionId();
            return $this->_getBaseUrl() . "/Terminal/default.aspx?merchantId=${merchantId}&transactionId=${transactionId}";
        }
        
        protected function _runOperation($operation){
             return $this->_performRequest('Process', array(
                'transactionId' => $this->getRequest()->getTransactionId(),
                'operation' => $operation
            ));
        }
        
        protected function _performRequest($endpointName, $params, $method = 'POST'){
            $endpoint = "/Netaxept/${endpointName}.aspx";
            
            $paramsWithAuth = array_merge($this->getMerchant()->asArray(), $params);
            
            $response = $this->_getClient()->request($method, $endpoint, array(
                'form_params' => $paramsWithAuth
            ));
            
            $parsedData = simplexml_load_string($response->getBody()->getContents());
           
            
            /* Need to make this more robust */
            if($response->getStatusCode() !== 200 || $parsedData->Error){
                $ex = new NetsException();
                $ex->setResponse((string)$response->getBody());
                //var_dump($ex);
                throw $ex;
                //throw new \Exception($parsedData->Error->Message);
            }
            
            return $parsedData;
        }
        
        
        protected function _getClient(){
            if(!isset($this->_client)){
                $baseUrl = $this->_getBaseUrl();
                $this->_client = new Client([
                    'base_uri' => $baseUrl,
                    'timeout'  => 2.0
                ]);
            }
            return $this->_client;
        }
        
        protected function _getBaseUrl(){
            return $this->getRequest()->isTestEnvironment() ? self::ENDPOINT_URL_TEST : self::ENDPOINT_URL_PROD;
        }



    }
?>