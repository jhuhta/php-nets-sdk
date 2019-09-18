<?php

namespace NetsSdk;

/**
 * The Price class.
 */
class Price {

  /**
   * The amount of this price.
   *
   * @var float
   */
  protected $amount;

  /**
   * The currency of this price.
   *
   * @var string
   */
  protected $currency;

  /**
   * Price constructor.
   *
   * @param float $amount
   *   The amount.
   * @param string $currency
   *   The currency.
   */
  public function __construct($amount = FALSE, string $currency = '') {
    if ($amount) {
      $this->setAmount($amount);
    }
    if ($currency) {
      $this->setCurrency($currency);
    }
  }

  /**
   * Gets the currency of this price.
   *
   * @return string
   *   The currency of this price.
   */
  public function getCurrency() {
    return $this->currency;
  }

  /**
   * Sets the currency code.
   *
   * @param string $currency
   *   The currency object.
   *
   * @return $this
   */
  public function setCurrency(string $currency) {
    $this->currency = $currency;
    return $this;
  }

  /**
   * Returns Nets API compatible amount representation.
   *
   * Strips away the decimal separator, but keeps all the numbers.
   * This is the way the Nets REST API needs the prices formatted.
   *
   * @return int
   *   The int amount, with decimal dot stripped away.
   */
  public function getStrippedDecimalInteger() {
    return (int) str_replace('.', '', sprintf('%.2f', $this->getAmount()));
  }

  /**
   * Gets the amount.
   *
   * @return float
   *   The amount.
   */
  public function getAmount() {
    return $this->amount;
  }

  /**
   * Sets the price amount.
   *
   * Use punctuation (.) instead of comma (,) on decimals.
   * For example 9 dollars and 99 cents is expressed 9.99.
   *
   * @param string $amount
   *   The amount as string, will be made a float.
   *
   * @return $this
   */
  public function setAmount(string $amount) {
    $this->amount = floatval($amount);
    return $this;
  }

}
