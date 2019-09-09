<?php

namespace NetsSdk;

use DomDocument;
use NetsSdk\Exceptions\AuthenticationException;
use NetsSdk\Exceptions\BBSExceptions\CardExpiredException;
use NetsSdk\Exceptions\BBSExceptions\DeniedBy3DSecureAuthenticationException;
use NetsSdk\Exceptions\BBSExceptions\GenericException;
use NetsSdk\Exceptions\BBSExceptions\InternalFailureException;
use NetsSdk\Exceptions\BBSExceptions\InvalidAmountException;
use NetsSdk\Exceptions\BBSExceptions\InvalidCardNumberException;
use NetsSdk\Exceptions\BBSExceptions\InvalidKidException;
use NetsSdk\Exceptions\BBSExceptions\InvalidTransactionException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedConfigIssuesContactNetaxeptException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedContactIssuerException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedFormatErrorException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedInvalidMerchantException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedInvalidSecurityCodeException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedLateResponseTryAgainException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedNoCardRecordException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedNoCheckingAccountException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedSystemMalfunctionException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedTemporarilyUnavailableException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedTransactionNotPermittedException;
use NetsSdk\Exceptions\BBSExceptions\IssuerRefusedTryAgainException;
use NetsSdk\Exceptions\BBSExceptions\NoTransactionException;
use NetsSdk\Exceptions\BBSExceptions\OriginalTransactionRejectedException;
use NetsSdk\Exceptions\BBSExceptions\TransactionAlreadyProcessedException;
use NetsSdk\Exceptions\BBSExceptions\TransactionAlreadyReversedException;
use NetsSdk\Exceptions\BBSExceptions\TransactionNotFoundException;
use NetsSdk\Exceptions\BBSExceptions\TransactionReachedMerchantTimoutException;
use NetsSdk\Exceptions\BBSExceptions\UnknownBBSException;
use NetsSdk\Exceptions\GenericError;
use NetsSdk\Exceptions\MerchantTranslationException;
use NetsSdk\Exceptions\NotSupportedException;
use NetsSdk\Exceptions\QueryException;
use NetsSdk\Exceptions\SecurityException;
use NetsSdk\Exceptions\UniqueTransactionIdException;
use NetsSdk\Exceptions\ValidationException;

/**
 * Tries to figure out what exception occured, and throws a proper exception
 * that can be handled. Exceptions has been implemented based on the
 * documentation (which might change)
 * https://shop.nets.eu/web/partners/exceptions
 */
class ExceptionResolver {

  /**
   * ExceptionResolver constructor.
   *
   * @param $rawXmlStringFromApi
   *   The XML response string.
   *
   * @throws \NetsSdk\Exceptions\AuthenticationException
   * @throws \NetsSdk\Exceptions\BBSExceptions\CardExpiredException
   * @throws \NetsSdk\Exceptions\BBSExceptions\DeniedBy3DSecureAuthenticationException
   * @throws \NetsSdk\Exceptions\BBSExceptions\GenericException
   * @throws \NetsSdk\Exceptions\BBSExceptions\InternalFailureException
   * @throws \NetsSdk\Exceptions\BBSExceptions\InvalidAmountException
   * @throws \NetsSdk\Exceptions\BBSExceptions\InvalidCardNumberException
   * @throws \NetsSdk\Exceptions\BBSExceptions\InvalidKidException
   * @throws \NetsSdk\Exceptions\BBSExceptions\InvalidTransactionException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedConfigIssuesContactNetaxeptException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedContactIssuerException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedFormatErrorException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedInvalidMerchantException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedLateResponseTryAgainException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedNoCardRecordException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedNoCheckingAccountException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedSystemMalfunctionException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedTemporarilyUnavailableException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedTransactionNotPermittedException
   * @throws \NetsSdk\Exceptions\BBSExceptions\IssuerRefusedTryAgainException
   * @throws \NetsSdk\Exceptions\BBSExceptions\NoTransactionException
   * @throws \NetsSdk\Exceptions\BBSExceptions\OriginalTransactionRejectedException
   * @throws \NetsSdk\Exceptions\BBSExceptions\TransactionAlreadyProcessedException
   * @throws \NetsSdk\Exceptions\BBSExceptions\TransactionAlreadyReversedException
   * @throws \NetsSdk\Exceptions\BBSExceptions\TransactionNotFoundException
   * @throws \NetsSdk\Exceptions\BBSExceptions\TransactionReachedMerchantTimoutException
   * @throws \NetsSdk\Exceptions\BBSExceptions\UnknownBBSException
   * @throws \NetsSdk\Exceptions\MerchantTranslationException
   * @throws \NetsSdk\Exceptions\NotSupportedException
   * @throws \NetsSdk\Exceptions\QueryException
   * @throws \NetsSdk\Exceptions\SecurityException
   * @throws \NetsSdk\Exceptions\UniqueTransactionIdException
   * @throws \NetsSdk\Exceptions\ValidationException
   */
  public function __construct($rawXmlStringFromApi) {
    $dom = new DomDocument();
    $dom->loadXml($rawXmlStringFromApi);
    $error = $dom->getElementsByTagName("Error")->item(0); // Error tag
    $type = $error->attributes->getNamedItem('type')->value; // Error tag's type attribute.
    $msg = $error->getElementsByTagName('Message')
      ->item(0)->nodeValue; // Message

    switch ($type) {
      case 'AuthenticationException':
        throw new AuthenticationException($msg);
        break;

      case 'BBSException':
        return $this->_resolveBbsException($rawXmlStringFromApi);
        break;

      case 'GenericError':
        throw new GenericException($msg);
        break;

      case 'MerchantTranslationException':
        throw new MerchantTranslationException($msg);
        break;

      case 'NotSupportedException':
        throw new NotSupportedException($msg);
        break;

      case 'SecurityException':
        throw new SecurityException($msg);
        break;

      case 'UniqueTransactionIdException':
        throw new UniqueTransactionIdException($msg);
        break;

      case 'ValidationException':
        throw new ValidationException($msg);
        break;

      case 'QueryException':
        throw new QueryException($msg);
        break;

      default:
        throw new GenericException($msg);
    }

  }

  /**
   * Handles the various BBSExceptions and tries to serve as much details as
   * possible. Exceptions and responses are based of the documentation (which
   * might change) https://shop.nets.eu/web/partners/response-codes
   */
  protected function _resolveBbsException($error) {
    $e = NULL;
    $parsedError = simplexml_load_string($error);
    $msg = $parsedError->Error->Message->__toString();
    $code = $parsedError->Error->Result->ResponseCode->__toString();
    $result = $parsedError->Error->Result;

    switch ($result->ResponseCode) {
      case '14':
        $e = new InvalidCardNumberException($msg, $code);
        break;

      case '25':
        $e = new TransactionNotFoundException($msg, $code);
        break;

      case '30':
        $e = new InvalidKidException($msg, $code);
        break;

      case '84':
        $e = new OriginalTransactionRejectedException($msg, $code);
        break;

      case '86':
        $e = new TransactionAlreadyReversedException($msg, $code);
        break;

      case '96':
        $e = new InternalFailureException($msg, $code);
        break;

      case '97':
        $e = new NoTransactionException($msg, $code);
        break;

      case '98':
        $e = new TransactionAlreadyProcessedException($msg, $code);
        break;

      case '99':
        $e = new GenericException($msg, $code);
        break;

      case 'MZ':
        $e = new DeniedBy3DSecureAuthenticationException($msg, $code);
        break;

      case 'T1':
        $e = new TransactionReachedMerchantTimoutException($msg, $code);
        break;

      case '01':
      case '02':
      case '41':
      case '43':
      case '51':
      case '59':
      case '61':
      case '62':
      case '93':
        $e = new IssuerRefusedContactIssuerException($msg, $code);
        break;

      case 03:
        $e = new IssuerRefusedInvalidMerchantException($msg, $code);
        break;

      case '04':
      case '05':
      case '06':
      case '07':
      case '08':
      case '09':
      case '10':
      case '14':
      case '15':
      case '25':
      case '34':
      case '35':
      case '36':
      case '37':
      case '60':
      case '78':
      case '79':
      case '80':
      case 'C9':
      case 'N0':
      case 'P1':
      case 'P9':
      case 'T3':
      case 'T8':
        $e = new IssuerRefusedException($msg, $code);
        break;

      case '12':
      case '39':
      case '77':
        $e = new InvalidTransactionException($msg, $code);
        break;

      case '13':
        $e = new InvalidAmountException($msg, $code);
        break;

      case '19':
        $e = new IssuerRefusedTryAgainException($msg, $code);
        break;

      case '30':
        $e = new IssuerRefusedFormatErrorException($msg, $code);
        break;

      case '33':
      case '54':
        $e = new CardExpiredException($msg, $code);
        break;

      case '52':
        $e = new IssuerRefusedNoCheckingAccountException($msg, $code);
        break;

      case '56':
        $e = new IssuerRefusedNoCardRecordException($msg, $code);
        break;

      case '57':
        $e = new IssuerRefusedTransactionNotPermittedException($msg, $code);
        break;

      case '68':
        $e = new IssuerRefusedLateResponseTryAgainException($msg, $code);
        break;

      case '86':
        $e = new IssuerRefusedConfigIssuesContactNetaxeptException($msg, $code);
        break;

      case '91':
      case '92':
      case '95':
        $e = new IssuerRefusedTemporarilyUnavailableException($msg, $code);
        break;

      case '96';
        $e = new IssuerRefusedSystemMalfunctionException($msg, $code);
        break;

      case 'N7':
        $e = new IssuerRefusedInvalidSecurityCodeException($msg, $code);
        break;

      default:
        $e = new UnknownBBSException($msg, $code);
        break;

    }
    $e->setPropertiesFromXml($result);
    throw $e;
  }
}
