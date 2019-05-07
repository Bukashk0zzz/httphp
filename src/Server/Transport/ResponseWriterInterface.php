<?php declare(strict_types=1);

namespace HTTPHP\Transport;

use Concurrent\Stream\WritableStream;

interface ResponseWriterInterface
{
    public function writeHeader(string $name, array $value): void;
    public function writeBody(string $data): void;
    public function withStatus(int $status): void;
}
