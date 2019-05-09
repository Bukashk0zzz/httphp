<?php declare(strict_types=1);

namespace HTTPHP\Transport;

interface ResponseWriterInterface
{
    /**
     * @param string $name
     * @param array  $value
     *
     * @return void
     */
    public function writeHeader(string $name, array $value): void;

    /**
     * @param string $data
     *
     * @return void
     */
    public function writeBody(string $data): void;

    /**
     * @param int $status
     *
     * @return void
     */
    public function withStatus(int $status): void;
}
