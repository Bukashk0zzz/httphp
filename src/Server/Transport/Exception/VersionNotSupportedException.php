<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\RFC723x;

class VersionNotSupportedException extends TransportException
{
    public function __construct()
    {
        parent::__construct(RFC723x::STATUS_HTTP_VERSION_NOT_SUPPORTED);
    }
}
