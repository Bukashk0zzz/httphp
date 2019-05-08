<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\RFC723x;
use RuntimeException;
use Throwable;

class TransportException extends RuntimeException
{
    public function __construct(?int $code = null, ?string $message = null, Throwable $previous = null)
    {
        if ($message === null && $code !== null) {
            $message = RFC723x::REASONS[$code] ?? null;
        }
        parent::__construct($message, $code, $previous);
    }
}
