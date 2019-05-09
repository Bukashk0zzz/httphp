<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\Status;

class NotImplementedException extends TransportException
{
    public function __construct()
    {
        parent::__construct(Status::NOT_IMPLEMENTED);
    }
}
