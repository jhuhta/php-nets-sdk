<?php

namespace NetsSdk;

/**
 * Class Merchant.
 */
class Merchant {

  use ArrayRepresentationTrait;

  /**
   * The merchant id.
   *
   * @var string
   */
  protected $merchantId;

  /**
   * The access token.
   *
   * @var string
   */
  protected $token;

  /**
   * Merchant constructor.
   *
   * @param string $merchantId
   *   The merchant id.
   * @param string $accessToken
   *   The access token.
   */
  public function __construct(string $merchantId = '', string $accessToken = '') {
    if ($merchantId) {
      $this->setMerchantId($merchantId);
    }
    if ($accessToken) {
      $this->setAccessToken($accessToken);
    }
  }

  /**
   * Sets the access token.
   *
   * @param string $accessToken
   *   The access token string.
   *
   * @return $this
   */
  public function setAccessToken(string $accessToken) {
    $this->token = $accessToken;
    return $this;
  }

  /**
   * Gets the merchant id.
   *
   * @return string
   *   The merchant id.
   */
  public function getMerchantId() {
    return $this->merchantId;
  }

  /**
   * Sets the merchant id.
   *
   * @param $merchantId
   *   The merchant id.
   *
   * @return $this
   */
  public function setMerchantId($merchantId) {
    $this->merchantId = $merchantId;
    return $this;
  }

  /**
   * Gets the access token.
   *
   * @return string
   *   The access token string.
   */
  public function getToken() {
    return $this->token;
  }

}
