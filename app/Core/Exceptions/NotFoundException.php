<?php
namespace App\Core\Exceptions;

/**
 * Thrown when a requested resource or route is not found (404)
 */
class NotFoundException extends \RuntimeException
{
    public function __construct(string $message = 'Not Found', int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
