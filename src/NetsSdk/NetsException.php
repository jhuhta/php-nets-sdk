<?php

namespace NetsSdk;

use Exception;
use DomDocument;

class NetsException extends Exception {
    
    protected $_type;
    protected $_bbsResult;
    
    /**
     * Returns exception type given by NETS
     * @return string
     */
    public function getType(){
        return $this->_type;
    }

    public function getErrorCode(){
        if($this->_type === 'BBSException'){
            if(array_key_exists('ResponseCode', $this->_bbsResult)){
                return $this->_bbsResult['ResponseCode'];
            }
        }

        return false;
    }

    /**
     * Returns array of items. 
     * Only applies for Exceptions of type BBSException
     * @return array
     */
    public function getBbsResult(){
        return $this->_bbsResult;
    }

    /**
     * Dissects response object to determine type of exception.
     * A list is available here:
     * https://shop.nets.eu/web/partners/exceptions
     * At the time of writing, only BBSException differs in object structure.
     */
    public function setResponse($responseObj){

        $dom = new DomDocument();
        $dom->loadXml($responseObj);
        $ex = $dom->getElementsByTagName('Exception')->item(0);
        $error = $dom->getElementsByTagName("Error")->item(0);
        $type = $error->attributes->getNamedItem('type')->value;
        $message = str_replace("\n", "", trim($error->nodeValue));
        
        
        $this->_type = $type;
        $this->message = $message; 
        
        if($this->_type === 'BBSException'){
            $result = $error->getElementsByTagName('Result')->item(0);
            $items = array();

            foreach($result->childNodes as $child){
                $items[$child->nodeName] = $child->nodeValue;
            }

            $this->_bbsResult = $items;
        }
    }
    
}
