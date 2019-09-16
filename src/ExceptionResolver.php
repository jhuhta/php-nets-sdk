<?php

namespace NetsSdk;

use DomDocument;
use NetsSdk\Exceptions\AuthenticationException;
use NetsSdk\Exceptions\BBSException;
use NetsSdk\Exceptions\GenericException;
use NetsSdk\Exceptions\MerchantTranslationException;
use NetsSdk\Exceptions\NotSupportedException;
use NetsSdk\Exceptions\QueryException;
use NetsSdk\Exceptions\SecurityException;
use NetsSdk\Exceptions\UniqueTransactionIdException;
use NetsSdk\Exceptions\ValidationException;

/**
 * Class ExceptionResolver.
 *
 * Tries to figure out what exception occured, and throws a proper exception
 * that can be handled. Exceptions has been implemented based on the
 * documentation (which might change)
 * https://shop.nets.eu/web/partners/exceptions
 *
 * This class doesn't instantiate but throws a resolved exception instead.
 */
class ExceptionResolver {

  /**
   * ExceptionResolver constructor.
   *
   * @param $rawXmlStringFromApi
   *   The XML response string.
   *
   * @throws \NetsSdk\Exceptions\AuthenticationException
   * @throws \NetsSdk\Exceptions\MerchantTranslationException
   * @throws \NetsSdk\Exceptions\NotSupportedException
   * @throws \NetsSdk\Exceptions\QueryException
   * @throws \NetsSdk\Exceptions\SecurityException
   * @throws \NetsSdk\Exceptions\UniqueTransactionIdException
   * @throws \NetsSdk\Exceptions\ValidationException
   * @throws \NetsSdk\Exceptions\GenericException
   * @throws \NetsSdk\Exceptions\BBSException
   */
  public function __construct($rawXmlStringFromApi) {
    $dom = new DomDocument();
    $dom->loadXml($rawXmlStringFromApi);
    // Error tag
    $error = $dom->getElementsByTagName("Error")->item(0);
    // Error tag's type attribute.
    $type = $error->attributes->getNamedItem('type')->value;
    // Message.
    $message = $error->getElementsByTagName('Message')->item(0)->nodeValue;

    switch ($type) {
      case 'AuthenticationException':
        throw new AuthenticationException($message);

      case 'BBSException':
        $this->resolveBbsException($rawXmlStringFromApi);
        break;

      case 'GenericError':
        throw new GenericException($message);

      case 'MerchantTranslationException':
        throw new MerchantTranslationException($message);

      case 'NotSupportedException':
        throw new NotSupportedException($message);

      case 'SecurityException':
        throw new SecurityException($message);

      case 'UniqueTransactionIdException':
        throw new UniqueTransactionIdException($message);

      case 'ValidationException':
        throw new ValidationException($message);

      case 'QueryException':
        throw new QueryException($message);

      default:
        throw new GenericException($message);
    }

  }

  /**
   * Handles the various BBSExceptions and tries to serve as much details as
   * possible. Exceptions and responses are based of the documentation (which
   * might change) https://shop.nets.eu/web/partners/response-codes
   *
   * We're not differentiating between BBSException types, as the error codes
   * overlap and we're not able to reliably resolve the correct code.
   *
   * @throws \NetsSdk\Exceptions\BBSException
   */
  protected function resolveBbsException($error) {
    $parsedError = simplexml_load_string($error);
    $message = sprintf('%s: %s', $parsedError->Error->Message->__toString(),
      $parsedError->Error->Result->ResponseText->__toString());
    $code = $parsedError->Error->Result->ResponseCode->__toString();
    $result = $parsedError->Error->Result;
    $e = new BBSException($message, $code);
    $e->setPropertiesFromXml($result);
    throw $e;
  }
}
