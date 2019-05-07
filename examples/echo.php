<?php

use HTTPHP\Handler\RequestHandlerInterface;
use HTTPHP\Server;
use HTTPHP\Transport\ResponseWriterInterface;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

require __DIR__ . '/../vendor/autoload.php';

$log = new Logger('log');
$log->pushHandler(new ErrorLogHandler());

$server = new Server(new class implements RequestHandlerInterface {
    public function __invoke(RequestInterface $request, ResponseWriterInterface $writer): ?ResponseInterface
    {
        $writer->writeBody(var_export($request, true));
        return null;
    }
}, $log);

$server->listen();
