<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\Status;

class LengthRequiredException extends TransportException
{
    public function __construct()
    {
        parent::__construct(Status::LENGTH_REQUIRED);
    }
}
