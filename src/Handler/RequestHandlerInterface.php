<?php declare(strict_types=1);

namespace HTTPHP\Handler;

use HTTPHP\Transport\ResponseWriterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RequestHandlerInterface
{
    public function __invoke(RequestInterface $request, ResponseWriterInterface $writer): ?ResponseInterface;
}
