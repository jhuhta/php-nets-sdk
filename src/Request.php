<?php

namespace NetsSdk;

use NetsSdk\Exceptions\GenericException;

/**
 * Class Request.
 *
 * This class doesn't set any properties via constructor but uses setter methods
 * instead.
 *
 * @see https://shop.nets.eu/web/partners/register
 */
class Request {

  use ArrayRepresentationTrait;

  /**
   * The order number.
   *
   * @var string
   */
  protected $orderNumber;

  /**
   * The customer first name.
   *
   * @var string
   */
  protected $customerFirstName;

  /**
   * The customer last name.
   *
   * @var string
   */
  protected $customerLastName;

  /**
   * The customer email.
   *
   * @var string
   */
  protected $customerEmail;

  /**
   * The order description.
   *
   * @var string
   */
  protected $orderDescription;

  /**
   * The redirect url.
   *
   * @var string
   */
  protected $redirectUrl;

  /**
   * The amount.
   *
   * Not to be accessed directly, but through the price property.
   *
   * @var string
   */
  protected $amount;

  /**
   * The currency code.
   *
   * Not to be accessed directly, but through the price property.
   *
   * @var string
   */
  protected $currencyCode;

  /**
   * The transaction language.
   *
   * @var string
   */
  protected $language = 'en_GB';

  /**
   * The transaction id.
   *
   * @var string
   */
  protected $transactionId;

  /**
   * The price object.
   *
   * @var \NetsSdk\Price
   */
  protected $price;

  /**
   * The payment method action list.
   *
   * @var array
   */
  protected $paymentMethodActionList;

  /**
   * The transaction reference number.
   *
   * @var string
   *
   * @see https://shop.nets.eu/web/partners/register
   * @see https://shop.nets.eu/web/partners/transaction-reference
   */
  protected $transactionReconRef;

  /**
   * Gets the transaction id.
   *
   * Transaction ID is a unique ID identifying each transaction within the
   * Merchant ID in Netaxept at any point.
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
   * Transaction ID is a unique ID identifying each transaction within the
   * Merchant ID in Netaxept at any point. If Transaction ID is omitted,
   * Netaxept will generate a unique Transaction ID for the transaction.
   *
   * @param string $transactionId
   *   The transaction id, max length = 32.
   *
   * @return $this
   */
  public function setTransactionId(string $transactionId) {
    $this->transactionId = $this->truncate($transactionId, 32);
    return $this;
  }

  /**
   * A transaction identifier defined by the merchant.
   *
   * @return string
   *   The order number.
   */
  public function getOrderNumber() {
    return $this->orderNumber;
  }

  /**
   * A transaction identifier defined by the merchant.
   *
   * Nets recommends to generate each transaction a unique order number but if
   * wanted the same order number can be used several times. Digits and letters
   * are allowed except special characters and scandinavian letters like Æ Ø Å
   * Ä Ö.
   *
   * @param string $orderNumber
   *   The order number string.
   *
   * @return $this
   */
  public function setOrderNumber(string $orderNumber) {
    $this->orderNumber = $orderNumber;
    return $this;
  }

  /**
   * Gets the Price object.
   *
   * @return Price
   *   The price.
   */
  public function getPrice() {
    return $this->price;
  }

  /**
   * Sets the price via a price object.
   *
   * Also sets amount and currencyCode property used when creating transaction.
   * This is important when building a array representation of this object.
   *
   * @param Price $price
   *   The Price object.
   *
   * @return $this
   */
  public function setPrice(Price $price) {
    if ($price === NULL) {
      throw new GenericException('Tried to set null price for a Request.');
    }
    $this->price = $price;
    $this->amount = $price->getStrippedDecimalInteger();
    $this->currencyCode = $price->getCurrency();
    return $this;
  }

  /**
   * Gets the customer first name.
   *
   * @return string
   *   The customer first name.
   */
  public function getCustomerFirstName() {
    return $this->customerFirstName;
  }

  /**
   * Sets the customer's first name.
   *
   * @param string $customerFirstName
   *   The first name, max length = 64.
   *
   * @return $this
   */
  public function setCustomerFirstName(string $customerFirstName) {
    $this->customerFirstName = $this->truncate($customerFirstName, 64);
    return $this;
  }

  /**
   * Gets the customer first name.
   *
   * @return string
   *   The first name.
   */
  public function getCustomerLastName() {
    return $this->customerLastName;
  }

  /**
   * Sets the customer's last name.
   *
   * @param string $customerLastName
   *   The last name, max length = 64.
   *
   * @return $this
   */
  public function setCustomerLastName(string $customerLastName) {
    $this->customerLastName = $this->truncate($customerLastName, 64);
    return $this;
  }

  /**
   * Gets the customer email address.
   *
   * @return string
   *   The email.
   */
  public function getCustomerEmail() {
    return $this->customerEmail;
  }

  /**
   * Sets the customer's email address.
   *
   * @param string $customerEmail
   *   (Max Length: 128)
   *
   * @return $this
   */
  public function setCustomerEmail($customerEmail) {
    $this->customerEmail = $this->truncate($customerEmail, 128);
    return $this;
  }

  /**
   * Gets the order description.
   *
   * @return string
   *   The order description.
   */
  public function getOrderDescription() {
    return $this->orderDescription;
  }

  /**
   * Sets the order description.
   *
   * Free-format textual description determined by the merchant for the
   * transaction. This can be HTML-formatted. If you are using Netaxept hosted
   * payment window, this description will appear in the payment window for the
   * customer. Unlike the other fields, the order description will not cause
   * the call to fail if it exceeds its maximum length, rather the field will
   * be truncated to its maximum length.
   *
   * @param string $orderDescription
   *   The order description, max length 1500.
   *
   * @return $this
   */
  public function setOrderDescription($orderDescription) {
    $this->orderDescription = $this->truncate($orderDescription, 1500);
    return $this;
  }

  /**
   * Gets the redirect url.
   *
   * @return string
   *   The url.
   */
  public function getRedirectUrl() {
    return $this->redirectUrl;
  }

  /**
   * Sets the redirect url.
   *
   * Indicates where to send the customer when the transaction after the
   * Register call and Terminal phase. This URL can contain GET parameters. The
   * redirect URL is optional when using "AutoAuth", and shouldn't be used with
   * Call centre transactions.
   *
   * @param string $redirectUrl
   *   The redirect url, max length 256.
   *
   * @return $this
   */
  public function setRedirectUrl($redirectUrl) {
    $this->redirectUrl = $this->truncate($redirectUrl, 256);
    return $this;
  }

  /**
   * Gets the transaction language.
   *
   * @return string
   *   The language as a locale string.
   */
  public function getLanguage() {
    return $this->language;
  }

  /**
   * Sets the transaction language.
   *
   * @param string $language
   *   The locale string.
   *
   * @return $this
   */
  public function setLanguage(string $language) {
    $this->language = $language;
    return $this;
  }

  /**
   * Sets the payment method action list.
   *
   * @param array $methodActionList
   *   The list.
   *
   * @return $this
   */
  public function setPaymentMethodActionList(array $methodActionList) {
    $this->paymentMethodActionList = $methodActionList;
    return $this;
  }

  /**
   * Gets the payment method action list.
   *
   * @return array
   *   The payment method action list array.
   */
  public function getPaymentMethodActionList() {
    return $this->paymentMethodActionList;
  }

  /**
   * Sets the reference number.
   *
   * @param string $referenceNumber
   *   The reference number.
   *
   * @return $this
   *
   * @see https://shop.nets.eu/web/partners/transaction-reference
   */
  public function setReferenceNumber(string $referenceNumber) {
    $this->transactionReconRef = $referenceNumber;
    return $this;
  }

  /**
   * Gets the reference number.
   *
   * @return string
   *   The reference number.
   */
  public function getReferenceNumber() {
    return $this->transactionReconRef;
  }

  /**
   * Helper method to cut a string, just for not repeating ourselves.
   *
   * @param string $string
   *   The string to cut.
   * @param int $length
   *   The length over which the string is truncated.
   *
   * @return string
   *   The truncated string, or original if it was shorter already.
   */
  private function truncate(string $string, int $length) {
    if (strlen($string) > $length) {
      return substr($string, 0, $length) || '';
    }
    return $string;
  }

}
