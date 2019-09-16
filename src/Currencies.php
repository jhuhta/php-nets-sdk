<?php

namespace NetsSdk;

/**
 * Currencies class.
 *
 * Contains a collection of currencies (ISO 4217).
 *
 * @TODO: Use Commercie\Currency instead.
 *
 * Usage:
 * Currencies::NorwegianKrone
 *
 * @see https://en.wikipedia.org/wiki/ISO_4217
 */
abstract class Currencies {

  // @codingStandardsIgnoreStart
  // Consts should be all uppercase.
  const NorwegianKrone = 'NOK';
  const DanishKrone = 'DKK';
  const SwedishKrone = "SEK";
  const Euro = "EUR";
  const AmericanDollar = "USD";
  // @codingStandardsIgnoreEnd

}
