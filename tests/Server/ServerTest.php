<?php declare(strict_types=1);

use Concurrent\Context;
use Concurrent\Task;
use Concurrent\Timer;
use GuzzleHttp\Client;
use HTTPHP\Handler\RequestHandlerInterface;
use HTTPHP\Server;
use HTTPHP\Transport\ResponseWriterInterface;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ServerTest extends TestCase
{
    protected function setUp(): void
    {
        $this->markTestIncomplete();
        parent::setUp();
    }

    public function testListen()
    {
        // create a log channel
        $log = new Logger('log');
        $log->pushHandler(new ErrorLogHandler());
        $log->info('START');


        $server = new Server(new class implements RequestHandlerInterface {
            public function __invoke(RequestInterface $request, ResponseWriterInterface $writer): ?ResponseInterface
            {
                $writer->write(var_export($request, true));
            }
        }, $log);

        $listenTask = Task::asyncWithContext(Context::background(), function() use($server) {
            $server->listen();
        });

        $task = Task::async(function () {
            (new Timer(3000))->awaitTimeout();
            $client = new Client();
            $response = $client->getAsync('http://127.0.0.1:3000/');
            $response->then(function() use($response) {
                $response->getBody()->getContents();
            });
        });

        Task::await($listenTask);
    }
}
