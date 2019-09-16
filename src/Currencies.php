<?php

namespace NetsSdk;

/**
 * Currencies class
 * 
 * Contains a collection of currencies (ISO 4217).
 * Missing your currency? Your more than welcome to add and submit a PR. 
 * 
 * Usage:
 * Currencies::NorwegianKrone
 * 
 * @see https://en.wikipedia.org/wiki/ISO_4217
 * 
 */
abstract class Currencies {
    const NorwegianKrone = 'NOK';
    const DanishKrone = 'DKK';
    const SwedishKrone = "SEK";
    const Euro = "EUR";
    const AmericanDollar = "USD";
}
