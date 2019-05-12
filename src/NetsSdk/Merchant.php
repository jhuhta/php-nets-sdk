<?php
    namespace NetsSdk;

    class Merchant {
        
        protected $_merchantId;
        protected $_accessToken;

        public function __construct($merchantId = false, $accessToken = false){
            if($merchantId) { $this->setMerchantId($merchantId); }
            if($accessToken){ $this->setAccessToken($accessToken); }
        }

        public function setMerchantId($merchantId){
            $this->_merchantId = $merchantId;
            return $this;
        }

        public function setAccessToken($accessToken){
            $this->_accessToken = $accessToken;
            return $this;
        }
    }
?>