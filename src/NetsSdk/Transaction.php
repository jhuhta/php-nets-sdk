<?php

namespace NetsSdk;

use GuzzleHttp\Client;
use NetsSdk\Exceptions\NetsException;

/**
 * Contains the Transaction class.
 */
class Transaction {

  /**
   * Nets production endpoint url.
   */
  const ENDPOINT_URL_PROD = 'https://epayment.nets.eu';

  /**
   * Nets test endpoint url.
   */
  const ENDPOINT_URL_TEST = 'https://test.epayment.nets.eu';

  /**
   * The transaction id.
   *
   * @var string
   */
  public $transactionId;

  /**
   * The Merchant object.
   *
   * @var \NetsSdk\Merchant
   */
  protected $_merchant;

  /**
   * The Request object.
   *
   * @var \NetsSdk\Request
   */
  protected $_request;

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $_client;

  /**
   * Whether we're running against test environment.
   *
   * @var bool
   */
  protected $_isTestEnvironment = FALSE;

  /**
   * Creates a new transaction.
   *
   * You can optionally pass merchant and request to the constructor.
   *
   * @param Merchant $merchant
   *   The Merchant object.
   * @param Request $request
   *   The Request object.
   * @param string $transactionId
   *   The transaction id.
   * @param bool $isTestEnviroment
   *   Are we running against the test environment.
   *
   * @throws \NetsSdk\Exceptions\NetsException
   *   Throws generic NetsException if the parameters are incorrect.
   */
  public function __construct(Merchant $merchant = NULL, Request $request = NULL, $transactionId = '', $isTestEnviroment = FALSE) {
    if ($merchant !== NULL) {
      if (!$merchant instanceof Merchant) {
        throw new NetsException("Expected either false or a Merchant object");
      }
      else {
        $this->setMerchant($merchant);
      }
    }

    if ($request !== NULL) {
      if (!$request instanceof Request) {
        throw new NetsException("Expected either false or a Request object");
      }
      else {
        $this->setRequest($request);
      }
    }

    if ($transactionId !== '') {
      $this->setTransactionId($transactionId);
    }

    $this->setIsTestEnvironment($isTestEnviroment);
  }

  /**
   * Sets the test environment flag.
   *
   * @param bool $isTest
   *   Is it test or not.
   *
   * @return $this
   */
  public function setIsTestEnvironment(bool $isTest) {
    $this->_isTestEnvironment = $isTest;
    return $this;
  }

  /**
   * Gets the test environment flag.
   *
   * @return bool
   *   Is it test or not.
   */
  public function isTestEnvironment() {
    return $this->_isTestEnvironment;
  }

  /**
   * Registers the request and returns object with generated/specified ID.
   *
   * @return string
   *   The transaction id.
   *
   * @throws \NetsSdk\Exceptions\NetsException
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function register() {
    $response = $this->_performRequest('Register', $this->getRequest()
      ->asArray());
    $this->transactionId = $response->TransactionId->__toString();
    return $this->transactionId;
  }

  /**
   * Execute the actual request.
   *
   * @param string $endpointName
   *   The endpoint name, such as 'Register'.
   * @param array $params
   *   Request parameters.
   * @param string $method
   *   The HTTP method to use.
   * @param bool $ignoreError
   *   Whether to ignore errors.
   *
   * @return \SimpleXMLElement
   *   The XML response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \NetsSdk\Exceptions\NetsException
   */
  protected function _performRequest(string $endpointName, array $params, string $method = 'POST', $ignoreError = FALSE) {
    $endpoint = "/Netaxept/${endpointName}.aspx";

    $paramsWithAuth = array_merge($this->getMerchant()->asArray(), $params);

    $response = $this->_getClient()->request($method, $endpoint, [
      'form_params' => $paramsWithAuth,
    ]);

    $parsedData = simplexml_load_string($response->getBody()->getContents());

    // Check if topmost object is indeed an error.
    if (!$ignoreError && $parsedData->getName() === 'Exception') {
      // Throws an exception defined in ExceptionResolver.
      new ExceptionResolver((string) $response->getBody());
    }

    return $parsedData;
  }

  /**
   * Returns the merchant object.
   *
   * @return Merchant
   *   The Merchant object.
   */
  public function getMerchant() {
    return $this->_merchant;
  }

  /**
   * Sets the merchant object.
   *
   * @param Merchant $merchant
   *   The Merchant object.
   *
   * @return $this
   */
  public function setMerchant(Merchant $merchant) {
    $this->_merchant = $merchant;
    return $this;
  }

  /**
   * Returns the HTTP client.
   *
   * @return \GuzzleHttp\Client
   *   The client.
   */
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

  /**
   * Gets the base url.
   *
   * @return string
   *   The base url.
   */
  protected function _getBaseUrl() {
    return $this->_isTestEnvironment ? self::ENDPOINT_URL_TEST : self::ENDPOINT_URL_PROD;
  }

  /**
   * Returns request object.
   *
   * @return Request
   *   The Request.
   */
  public function getRequest() {
    return $this->_request;
  }

  /**
   * Sets the request object.
   *
   * @param Request $request
   *   The request.
   *
   * @return $this
   */
  public function setRequest(Request $request) {
    $this->_request = $request;
    return $this;
  }

  /**
   * Runs the AUTH operation.
   *
   * @return \SimpleXMLElement
   *   The XML response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \NetsSdk\Exceptions\NetsException
   */
  public function authorize() {
    return $this->_runOperation('AUTH');
  }

  /**
   * Runs any given operation.
   *
   * @param string $operation
   *   The operation.
   *
   * @return \SimpleXMLElement
   *   The XML response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \NetsSdk\Exceptions\NetsException
   */
  protected function _runOperation(string $operation) {
    return $this->_performRequest('Process', [
      'transactionId' => $this->transactionId,
      'operation' => $operation,
    ]);
  }

  /**
   * Runs the CAPTURE operation.
   *
   * @return \SimpleXMLElement
   *   The XML response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \NetsSdk\Exceptions\NetsException
   */
  public function capture() {
    return $this->_runOperation("CAPTURE");
  }

  /**
   * Tells if the query is authorized.
   *
   * @param \SimpleXMLElement $query
   *   The query.
   *
   * @return bool
   *   Is the query authorized.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \NetsSdk\Exceptions\NetsException
   */
  public function isAuthorized(\SimpleXMLElement $query = NULL) {
    if (empty($query)) {
      $query = $this->query();
    }
    $stringBoolean = strtolower($query->Summary->Authorized->__toString());
    $actualBool = $stringBoolean === 'true' ? TRUE : FALSE;
    return $actualBool;
  }

  /**
   * Performs the query request.
   *
   * @return \SimpleXMLElement
   *   The XML response.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \NetsSdk\Exceptions\NetsException
   */
  public function query() {
    return $this->_performRequest('Query', [
      'transactionId' => $this->transactionId,
    ], 'GET', TRUE);
  }

  /**
   * Gets the captured amount from the query.
   *
   * @param \SimpleXMLElement $query
   *   The query.
   *
   * @return int
   *   The amount.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \NetsSdk\Exceptions\NetsException
   */
  public function getCapturedAmount(\SimpleXMLElement $query = NULL) {
    if (!isset($query)) {
      $query = $this->query();
    }
    return (int) $query->Summary->AmountCaptured->__toString();
  }

  /**
   * Gets the terminal url.
   *
   * @return string
   *   The url string.
   */
  public function getTerminalUrl() {
    $merchantId = $this->getMerchant()->getMerchantId();
    $transactionId = $this->getTransactionId();
    return $this->_getBaseUrl() . "/Terminal/default.aspx?merchantId=${merchantId}&transactionId=${transactionId}";
  }

  /**
   * Gets the transaction id.
   *
   * @return string
   *   The transaction id.
   */
  public function getTransactionId() {
    return $this->transactionId;
  }

  /**
   * Sets the transaction id.
   *
   * @param string $transactionId
   *   The id.
   *
   * @return $this
   */
  public function setTransactionId(string $transactionId) {
    $this->transactionId = $transactionId;
    return $this;
  }

}
