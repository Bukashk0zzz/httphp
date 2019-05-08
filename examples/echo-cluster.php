<?php

use HTTPHP\Cluster;
use HTTPHP\Handler\RequestHandlerInterface;
use HTTPHP\Transport\ResponseWriterInterface;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

require __DIR__ . '/../vendor/autoload.php';

$logger = new Logger('log');
$logger->pushHandler(new ErrorLogHandler());

$server = new Cluster(new class implements RequestHandlerInterface {
    public function __invoke(RequestInterface $request, ResponseWriterInterface $writer): ?ResponseInterface
    {
        $writer->writeBody(var_export($request, true));
        return null;
    }
}, $logger, 128 * 1024 * 1024, 2 ** 16 );

$server->listen();
