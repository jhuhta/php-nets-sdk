<?php

namespace NetsSdk\Exceptions;

use NetsSdk\Exceptions\NetsException;

class BBSException extends NetsException { 

    public $issuerId;
    public $responseCode;
    public $responseText;
    public $responseSrc;
    public $transactionId;
    public $executionTime;
    public $merchantId;
    public $messageId;

    public function setPropertiesFromXml($obj){  
        $this->issuerId = $obj->IssuerId->__toString();
        $this->responseCode = $obj->ResponseCode->__toString();
        $this->responseText = $obj->ResponseText->__toString();
        $this->responseSrc = $obj->ResponseSource->__toString();
        $this->transactionId = $obj->TransactionId->__toString();
        $this->merchantId = $obj->MerchantId->__toString();
        $this->messageId = $obj->MessageId->__toString();
    }
}
