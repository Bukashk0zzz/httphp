<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\RFC723x;

class PayloadTooLargeException extends TransportException
{
    /**
     * @var int
     */
    private $limit;

    public function __construct(int $limit)
    {
        parent::__construct(RFC723x::STATUS_PAYLOAD_TOO_LARGE);
        $this->limit = $limit;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
