<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\RFC723x;

class LengthRequiredException extends TransportException
{
    public function __construct()
    {
        parent::__construct(RFC723x::STATUS_LENGTH_REQUIRED);
    }
}
