<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\RFC723x;

class UpgradeRequiredException extends TransportException
{
    private $requestedVersion;
    public function __construct(string $requestedVersion)
    {
        parent::__construct(RFC723x::STATUS_UPGRADE_REQUIRED);
        $this->requestedVersion = $requestedVersion;
    }

    /**
     * @return string
     */
    public function getRequestedVersion(): string
    {
        return $this->requestedVersion;
    }
}
