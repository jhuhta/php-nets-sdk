<?php
    namespace NetsSdk;

    class Merchant {
        
        public $merchantId;
        public $token;

        public function __construct($merchantId = false, $accessToken = false){
            if($merchantId) { $this->setMerchantId($merchantId); }
            if($accessToken){ $this->setAccessToken($accessToken); }
        }

        public function setMerchantId($merchantId){
            $this->merchantId = $merchantId;
            return $this;
        }

        public function setAccessToken($accessToken){
            $this->token = $accessToken;
            return $this;
        }
        
        public function asArray(){
            return json_decode(json_encode($this), true);
        }
        
        public function getMerchantId(){
            return $this->merchantId;
        }
        
        public function getToken(){
            return $this->token;
        }
    }
?>