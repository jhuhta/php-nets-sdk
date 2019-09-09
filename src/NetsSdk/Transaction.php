<?php

namespace NetsSdk;

use GuzzleHttp\Client;

class Transaction {

  const ENDPOINT_URL_PROD = 'https://epayment.nets.eu';

  const ENDPOINT_URL_TEST = 'https://test.epayment.nets.eu';

  public $transactionId;

  protected $_merchant;

  protected $_request;

  protected $_client;

  protected $_isTestEnvironment = FALSE;

  /**
   * Creates a new transaction.
   * You can optionally pass merchant and request to the constructor.
   *
   * @param Merchant $merchant
   * @param Request $request
   *
   * @throws Exception
   */
  public function __construct($merchant = FALSE, $request = FALSE, $transactionId = FALSE, $isTestEnviroment = FALSE) {
    if ($merchant !== FALSE) {
      if (!$merchant instanceof Merchant) {
        throw new Exception("Expected either false or a Merchant object");
      }
      else {
        $this->setMerchant($merchant);
      }
    }

    if ($request !== FALSE) {
      if (!$request instanceof Request) {
        throw new Exception("Expected either false or a Request object");
      }
      else {
        $this->setRequest($request);
      }
    }

    if ($transactionId !== FALSE) {
      $this->setTransactionId($transactionId);
    }

    $this->setIsTestEnvironment($isTestEnviroment);


  }

  public function setIsTestEnvironment($bool) {
    $this->_isTestEnvironment = $bool;
    return $this;
  }

  public function isTestEnvironment() {
    return $this->_isTestEnvironment;
  }

  /**
   * Registers the request and returns object with generated/specified ID (if
   * successful).
   *
   * @param type $request
   *
   * @return Request
   * @throws Exception
   */
  public function register() {
    $response = $this->_performRequest('Register', $this->getRequest()
      ->asArray());
    $this->transactionId = $response->TransactionId->__toString();
    return $this->transactionId;
  }

  protected function _performRequest($endpointName, $params, $method = 'POST', $ignoreError = FALSE) {
    $endpoint = "/Netaxept/${endpointName}.aspx";

    $paramsWithAuth = array_merge($this->getMerchant()->asArray(), $params);

    $response = $this->_getClient()->request($method, $endpoint, [
      'form_params' => $paramsWithAuth,
    ]);

    $parsedData = simplexml_load_string($response->getBody()->getContents());

    // Check if topmost object is indeed an error.
    if ($parsedData->getName() === 'Exception') {
      return new ExceptionResolver((string) $response->getBody());
    }

    return $parsedData;
  }

  /**
   * Returns the merchant object.
   *
   * @return Merchant
   */
  public function getMerchant() {
    return $this->_merchant;
  }

  /**
   * Sets the merchant object.
   *
   * @param Merchant $merchant
   *
   * @return $this
   */
  public function setMerchant(Merchant $merchant) {
    $this->_merchant = $merchant;
    return $this;
  }

  protected function _getClient() {
    if (!isset($this->_client)) {
      $baseUrl = $this->_getBaseUrl();
      $this->_client = new Client([
        'base_uri' => $baseUrl,
        'timeout' => 2.0,
      ]);
    }
    return $this->_client;
  }

  protected function _getBaseUrl() {
    return $this->_isTestEnvironment ? self::ENDPOINT_URL_TEST : self::ENDPOINT_URL_PROD;
  }

  /**
   * Returns request object.
   *
   * @return Request
   */
  public function getRequest() {
    return $this->_request;
  }

  /**
   * Sets the request object.
   *
   * @param Request $request
   *
   * @return $this
   */
  public function setRequest(Request $request) {
    $this->_request = $request;
    return $this;
  }

  public function authorize() {
    return $this->_runOperation('AUTH');
  }

  protected function _runOperation($operation) {
    return $this->_performRequest('Process', [
      'transactionId' => $this->transactionId,
      'operation' => $operation,
    ]);
  }

  public function capture() {
    return $this->_runOperation("CAPTURE");
  }

  public function isAuthorized($query = NULL) {
    if (!isset($query)) {
      $query = $this->query();
    }
    $stringBoolean = strtolower($query->Summary->Authorized->__toString());
    $actualBool = $stringBoolean === 'true' ? TRUE : FALSE;
    return $actualBool;
  }

  public function query() {
    return $this->_performRequest('Query', [
      'transactionId' => $this->transactionId,
    ], 'GET', TRUE);
  }

  public function getCapturedAmount($query = NULL) {
    if (!isset($query)) {
      $query = $this->query();
    }
    return (int) $query->Summary->AmountCaptured->__toString();
  }

  public function isFullAmountCredited($query = NULL) {

  }

  public function getTerminalUrl() {
    $merchantId = $this->getMerchant()->getMerchantId();
    $transactionId = $this->getTransactionId();
    return $this->_getBaseUrl() . "/Terminal/default.aspx?merchantId=${merchantId}&transactionId=${transactionId}";
  }

  public function getTransactionId() {
    return $this->transactionId;
  }

  public function setTransactionId($transactionId) {
    $this->transactionId = $transactionId;
    return $this;
  }


}

?>
