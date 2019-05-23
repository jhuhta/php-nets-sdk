<?php

    namespace NetsSdk;
    
    use NetsSdk\Merchant;
    use NetsSdk\Request;
    use NetsSdk\ExceptionResolver;

    use NetsSdk\Exceptions\TransactionNotFoundException;

    use GuzzleHttp\Client;
    use DomDocument;

    class Transaction {
        
        public $transactionId;
        
        protected $_merchant;
        protected $_request;
        protected $_client;
        
        protected $_isTestEnvironment = false;
        
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
        public function __construct($merchant = false, $request = false, $transactionId = false, $isTestEnviroment = false){
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

            if($transactionId !== false){
                $this->setTransactionId($transactionId);
            }
            
            $this->setIsTestEnvironment($isTestEnviroment);
            
            
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

        public function setTransactionId($transactionId){
            $this->transactionId = $transactionId;
            return $this;
        }
        
        public function setIsTestEnvironment($bool){
            $this->_isTestEnvironment = $bool;
            return $this;
        }
        
        public function isTestEnvironment(){
            return $this->_isTestEnvironment;
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
            return $this->_performRequest('Query', array(
                'transactionId' => $this->transactionId
            ), 'GET', true);
        }
        
        public function isAuthorized($query = null){
            if(!isset($query)){ $query = $this->query();}
            $stringBoolean = strtolower($query->Summary->Authorized->__toString());
            $actualBool = $stringBoolean === 'true' ? true : false;
            return $actualBool;
        }
        
        
        public function getCapturedAmount($query = null){
            if(!isset($query)){ $query = $this->query();}
            return (int) $query->Summary->AmountCaptured->__toString();   
        }
        
        public function isFullAmountCredited($query = null){
            
        }
        
        public function getTerminalUrl(){
            $merchantId = $this->getMerchant()->getMerchantId();
            $transactionId = $this->getTransactionId();
            return $this->_getBaseUrl() . "/Terminal/default.aspx?merchantId=${merchantId}&transactionId=${transactionId}";
        }
        
        protected function _runOperation($operation){
             return $this->_performRequest('Process', array(
                'transactionId' => $this->transactionId,
                'operation' => $operation
            ));
        }
        
        protected function _performRequest($endpointName, $params, $method = 'POST', $ignoreError = false){
            $endpoint = "/Netaxept/${endpointName}.aspx";
            
            $paramsWithAuth = array_merge($this->getMerchant()->asArray(), $params);
            
            $response = $this->_getClient()->request($method, $endpoint, array(
                'form_params' => $paramsWithAuth
            ));
            
            $parsedData = simplexml_load_string($response->getBody()->getContents());
        
            // Check if topmost object is indeed an error.
            if($parsedData->getName() === 'Exception'){
                return new ExceptionResolver((string)$response->getBody());
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
            return $this->_isTestEnvironment ? self::ENDPOINT_URL_TEST : self::ENDPOINT_URL_PROD;
        }



    }
?>