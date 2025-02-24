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
   * The customer address 1;
   *
   * @var string
   */
  protected $customerAddress1;

  /**
   * The customer address 2;
   *
   * @var string
   */
  protected $customerAddress2;

  /**
   * The customer postal/zip code;
   *
   * @var string
   */
  protected $customerPostcode;

  /**
   * The customer town;
   *
   * @var string
   */
  protected $customerTown;

  /**
   * The customer country;
   *
   * @var string
   */
  protected $customerCountry;

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
   * The payment method action list, as a json decoded string.
   *
   * @var string
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
   * Indicates which kind of recurring transaction to create.
   *
   * Valid values are 'S' and 'R'.
   *
   * @var string
   *
   * @see https://shop.nets.eu/web/partners/register
   */
  protected $recurringType;

  /**
   * Indicates how often the merchant is allowed to make withdrawals, in days.
   *
   * @var string
   *
   * @see https://shop.nets.eu/web/partners/register
   */
  protected $recurringFrequency;

  /**
   * The expiry date of the recurring deal, in YYYYMMDD.
   *
   * @var int
   *
   * @see https://shop.nets.eu/web/partners/register
   */
  protected $recurringExpiryDate;

  /**
   * Set single page terminal.
   *
   * @var string
   *
   * @see https://shop.nets.eu/web/partners/register
   */
  protected $terminalSinglePage;

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
   * @throws \NetsSdk\Exceptions\GenericException
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
   * Gets the customer address 1.
   *
   * @return string
   *   The address 1.
   */
  public function getCustomerAddress1() {
    return $this->customerAddress1;
  }

  /**
   * Sets the customer's address 1.
   *
   * @param string $customerAddress1
   *   The address 1 (Max Length: 64).
   *
   * @return $this
   */
  public function setCustomerAddress1($customerAddress1) {
    $this->customerAddress1 = $this->truncate($customerAddress1, 64);
    return $this;
  }

  /**
   * Gets the customer address 2.
   *
   * @return string
   *   The address 2.
   */
  public function getCustomerAddress2() {
    return $this->customerAddress2;
  }

  /**
   * Sets the customer's address 2.
   *
   * @param string $customerAddress2
   *   The address 2 (Max Length: 64).
   *
   * @return $this
   */
  public function setCustomerAddress2($customerAddress2) {
    $this->customerAddress2 = $this->truncate($customerAddress2, 64);
    return $this;
  }

  /**
   * Gets the customer postal code.
   *
   * @return string
   *   The postal code.
   */
  public function getCustomerPostcode() {
    return $this->customerPostcode;
  }

  /**
   * Sets the customer's postal code.
   *
   * @param string $customerPostcode
   *   The postal code (Max Length: 16).
   *
   * @return $this
   */
  public function setCustomerPostcode($customerPostcode) {
    $this->customerPostcode = $this->truncate($customerPostcode, 16);
    return $this;
  }

  /**
   * Gets the customer town.
   *
   * @return string
   *   The town.
   */
  public function getCustomerTown() {
    return $this->customerTown;
  }

  /**
   * Sets the customer's town.
   *
   * @param string $customerTown
   *   The town (Max Length: 16).
   *
   * @return $this
   */
  public function setCustomerTown($customerTown) {
    $this->customerTown = $this->truncate($customerTown, 16);
    return $this;
  }

  /**
   * Gets the customer country.
   *
   * @return string
   *   The country.
   */
  public function getCustomerCountry() {
    return $this->customerCountry;
  }

  /**
   * Sets the customer's country.
   *
   * @param string $customerCountry
   *   The country.
   *
   * @return $this
   */
  public function setCustomerCountry($customerCountry) {
    $this->customerCountry = $customerCountry;
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
    $this->paymentMethodActionList = json_encode($methodActionList);
    return $this;
  }

  /**
   * Gets the payment method action list.
   *
   * @return array
   *   The payment method action list array.
   */
  public function getPaymentMethodActionList() {
    return json_decode($this->paymentMethodActionList);
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
   * Sets the recurring type.
   *
   * @param string $recurringType
   *   The recurring type, either 'S' or 'R'.
   *
   * @return $this
   *
   * @see https://shop.nets.eu/web/partners/register
   */
  public function setRecurringType(string $recurringType) {
    $this->recurringType = $recurringType;
    return $this;
  }

  /**
   * Gets the recurring type.
   *
   * @return string
   *   The recurring type.
   */
  public function getRecurringType() {
    return $this->recurringType;
  }

  /**
   * Sets the minimum allowed frequency for recurring payments.
   *
   * @param string $frequency
   *   The frequency, in days: 0-365.
   *
   * @return $this
   */
  public function setRecurringFrequency(string $frequency) {
    $this->recurringFrequency = $frequency;
    return $this;
  }

  /**
   * Gets the minimum allowed frequency for recurring payments.
   *
   * @return string
   *   The frequency in days.
   */
  public function getRecurringFrequency() {
    return $this->recurringFrequency;
  }

  /**
   * The end date of the recurring agreement between merchant and the customer.
   *
   * @param string $date
   *   The date, in YYYYMMDD format.
   *
   * @return $this
   */
  public function setRecurringExpiryDate(string $date) {
    $this->recurringExpiryDate = (int) $date;
    return $this;
  }

  /**
   * The end date of the recurring agreement between merchant and the customer.
   *
   * @return string
   *   The date.
   */
  public function getRecurringExpiryDate() {
    return (string) $this->recurringExpiryDate;
  }

  /**
   * Sets the terminal single page toggle.
   *
   * @param bool $singlePage
   *   The value.
   *
   * @return $this
   */
  public function setTerminalSinglePage(bool $singlePage) {
    if ($singlePage) {
      $this->terminalSinglePage = 'true';
    }
    else {
      unset($this->terminalSinglePage);
    }
    return $this;
  }

  /**
   * Check if the terminal is set to single page.
   *
   * @return bool
   *   Boolean indicating whether the terminal is set to single page.
   */
  public function getTerminalSinglePage() {
    if ($this->terminalSinglePage === 'true') {
      return TRUE;
    }
    return FALSE;
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
