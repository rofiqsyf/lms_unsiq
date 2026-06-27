<?php
namespace App\Core\Exceptions;

/**
 * Thrown when user doesn't have permission to access a resource (403)
 */
class ForbiddenException extends \RuntimeException
{
    public function __construct(string $message = 'Forbidden', int $code = 403, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
