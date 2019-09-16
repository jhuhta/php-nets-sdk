<?php

namespace NetsSdk\Exceptions;

/**
 * Class BBSException.
 */
class BBSException extends NetsException {

  /**
   * The issuer id.
   *
   * @var string
   */
  public $issuerId;

  /**
   * The response code.
   *
   * @var string
   */
  public $responseCode;

  /**
   * The response text.
   *
   * @var string
   */
  public $responseText;

  /**
   * The response source.
   *
   * @var string
   */
  public $responseSrc;

  /**
   * The transaction id.
   *
   * @var string
   */
  public $transactionId;

  /**
   * The merchant id.
   *
   * @var string
   */
  public $merchantId;

  /**
   * The message id.
   *
   * @var string
   */
  public $messageId;

  /**
   * Sets the public properties according to the values of an XML object.
   */
  public function setPropertiesFromXml($obj) {
    $this->issuerId = $obj->IssuerId->__toString();
    $this->responseCode = $obj->ResponseCode->__toString();
    $this->responseText = $obj->ResponseText->__toString();
    $this->responseSrc = $obj->ResponseSource->__toString();
    $this->transactionId = $obj->TransactionId->__toString();
    $this->merchantId = $obj->MerchantId->__toString();
    $this->messageId = $obj->MessageId->__toString();
  }

}
