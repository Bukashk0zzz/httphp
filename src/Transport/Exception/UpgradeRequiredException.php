<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\Status;

class UpgradeRequiredException extends TransportException
{
    private $requestedVersion;
    public function __construct(string $requestedVersion)
    {
        parent::__construct(Status::UPGRADE_REQUIRED);
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
