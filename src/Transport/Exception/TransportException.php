<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\Status;
use RuntimeException;
use Throwable;

class TransportException extends RuntimeException
{
    public function __construct(?int $code = null, ?string $message = null, Throwable $previous = null)
    {
        if ($message === null && $code !== null) {
            $message = Status::REASONS[$code] ?? null;
        }
        parent::__construct($message, $code, $previous);
    }
}
