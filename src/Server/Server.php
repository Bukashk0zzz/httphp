<?php
declare(strict_types=1);

namespace HTTPHP;

use Concurrent\Awaitable;
use Concurrent\CancellationHandler;
use Concurrent\Channel;
use Concurrent\Context;
use Concurrent\Network\TcpServer;
use Concurrent\SignalWatcher;
use Concurrent\Task;
use HTTPHP\Handler\RequestHandlerInterface;
use HTTPHP\Transport\RequestReader;
use Psr\Log\LoggerInterface;

/**
 * Class Server
 * @package HTTPHP
 */
class Server
{
    protected $maxmemory;
    protected $maxrequests;
    /**
     * @var TcpServer|Server
     */
    protected $server;
    /**
     * @var Channel
     */
    protected $cancellation;
    protected $shutdown = false;

    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var RequestHandlerInterface
     */
    protected $handler;

    public function __construct(
        RequestHandlerInterface $handler,
        ?LoggerInterface $logger = null,
        ?int $maxmemory = null,
        ?int $maxrequests = null
    ) {
        $this->logger = $logger;
        $this->cancellation = new CancellationHandler();
        $this->handler = $handler;
        $this->maxmemory = $maxmemory;
        $this->maxrequests = $maxrequests;
    }

    public function listen(string $address = '0.0.0.0', int $port = 3000)
    {
        $this->registerSignalHandler();
        $this->createServer($address, $port);
        $this->startWorker();
    }

    public function shutdown(): void
    {
        $this->shutdown = true;
        ($this->cancellation)();

        if ($this->server) {
            $this->server->close();
        }
    }

    protected function createServer(string $address, int $port)
    {
        $this->server = TcpServer::bind($address, $port);
    }

    protected function startWorker(): Awaitable
    {
        $worker = new Worker(
            $this->server,
            $this->cancellation,
            new RequestReader(),
            $this->handler,
            $this->maxmemory,
            $this->maxrequests
        );

        $worker->setLogger($this->logger);

        $this->logger->info('WORKER CREATED');

        return $worker->start();
    }

    protected function registerSignalHandler()
    {
        Task::asyncWithContext(Context::current(), function () {
            $watcher = new SignalWatcher(SignalWatcher::SIGINT);
            $watcher->awaitSignal();

            $this->logger->debug('SIGINT received...');
            $this->shutdown();
        });
    }
}
