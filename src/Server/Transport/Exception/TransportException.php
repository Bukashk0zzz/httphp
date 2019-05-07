<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\RFC723x;

class TransportException extends \RuntimeException
{
    public function __construct(int $code = 0, string $message = '')
    {
        if ($message === '' && $code !== 0) {
            $message = RFC723x::REASONS[$code] ?? '';
        }
        parent::__construct($message, $code);
    }
}
