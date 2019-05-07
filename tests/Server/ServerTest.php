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
        }, false, $log);

        $listenTask = Task::asyncWithContext(Context::background(), function() use($server) {
            var_dump('listening');
            ob_flush();
            $server->listen();
            var_dump('exited');
            ob_flush();
        });

        var_dump('before_timeout');
        ob_flush();
        $task = Task::async(function () {
            var_dump('timeout');
            ob_flush();
            (new Timer(3000))->awaitTimeout();
            $client = new Client();
            var_dump('sending');
            ob_flush();
            $response = $client->getAsync('http://127.0.0.1:3000/');
            $response->then(function() {
                var_dump('sended');
                ob_flush();
                $a = $response->getBody()->getContents();
                var_dump($a);
                ob_flush();
            });
        });

        Task::await($listenTask);
    }
}
