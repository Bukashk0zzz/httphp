<?php declare(strict_types=1);

namespace HTTPHP\Transport\Exception;

use HTTPHP\RFC\RFC723x;

class BadRequestException extends TransportException
{
    /**
     * @var string|null
     */
    private $data;

    public function __construct(?string $data = null)
    {
        $this->data = $data;
        parent::__construct(RFC723x::STATUS_BAD_REQUEST);
    }

    public function getData(): ?string
    {
        return $this->data;
    }
}
