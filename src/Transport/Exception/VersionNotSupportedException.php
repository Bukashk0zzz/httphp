<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\Status;

class VersionNotSupportedException extends TransportException
{
    public function __construct()
    {
        parent::__construct(Status::HTTP_VERSION_NOT_SUPPORTED);
    }
}
