<?php
namespace App\Util\Exceptions;

use FOS\RestBundle\Util\ExceptionValueMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use App\Util\Exceptions\FormInvalidException;
use App\Util\Exceptions\ResourceNotFoundException;

/**
 * Custom ExceptionMessages that renders to json
 */
class ExceptionMessage
{
    /**
     * Converts an Exception to a Response.
     *
     * @param \Exception|\Throwable     $exception
     *
     * @return array
     */
    public function getStatusResponse(\Exception $exception) : Array
    {
      return $statusResponse = $this->getStatusCode($exception);
    }

    /**
     * Determines the status code to use for the response.
     *
     * @param \Exception $exception
     *
     * @return array
     */
    protected function getStatusCode(\Exception $exception) : Array
    {
      switch (get_class($exception)) {
      case FormInvalidException::class:
          $statusCode = Response::HTTP_BAD_REQUEST;
          $statusMessage = $exception->get_Message();
          break;
      case ResourceNotFoundException::class:
          $statusCode = Response::HTTP_NOT_FOUND;
          $statusMessage = $exception->get_Message();
          break;
      case HttpExceptionInterface::class:
          $statusCode = $exception->getStatusCode();
          $statusMessage = $exception->getMessage();
          break;
      default:
          $statusCode = 500;
          $statusMessage = $exception->getMessage();
          break;
      }

      return ['statusCode' => $statusCode, 'statusMessage' => $statusMessage];
    }
}
    