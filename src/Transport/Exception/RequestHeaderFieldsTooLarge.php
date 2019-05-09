<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\Status;

class RequestHeaderFieldsTooLarge extends TransportException
{
    /**
     * @var int
     */
    private $limit;

    public function __construct(int $limit)
    {
        parent::__construct(Status::REQUEST_HEADER_FIELDS_TOO_LARGE);
        $this->limit = $limit;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
