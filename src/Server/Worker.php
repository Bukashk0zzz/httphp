<?php declare(strict_types=1);

namespace HTTPHP;

use Concurrent\Awaitable;
use Concurrent\CancellationHandler;
use Concurrent\Context;
use Concurrent\Deferred;
use Concurrent\Network\SocketStream;
use Concurrent\Network\TcpSocket;
use Concurrent\Task;
use HTTPHP\Handler\RequestHandlerInterface;
use HTTPHP\Transport\Exception\TransportException;
use HTTPHP\Transport\Exception\UpgradeRequiredException;
use HTTPHP\Transport\RequestReaderInterface;
use HTTPHP\Transport\ResponseWriter;
use Psr\Log\LoggerAwareTrait;

class Worker
{
    use LoggerAwareTrait;

    /**
     * @var \Concurrent\Network\Server
     */
    private $server;
    /**
     * @var CancellationHandler
     */
    private $cancellation;
    /**
     * @var int
     */
    private $requests = 0;
    /**
     * @var int|null
     */
    private $maxmemory;
    /**
     * @var int|null
     */
    private $maxrequests;
    /**
     * @var RequestReaderInterface
     */
    private $reader;
    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * @var int|null
     */
    private $timeout;
    /**
     * @var int
     */
    private $id;

    public function __construct(
        \Concurrent\Network\Server $server,
        CancellationHandler $cancellation,
        RequestReaderInterface $reader,
        RequestHandlerInterface $handler,
        ?int $maxmemory = null,
        ?int $maxrequests = null,
        ?int $timeout = null
    )
    {
        $this->server = $server;
        $this->cancellation = $cancellation;
        $this->maxmemory = $maxmemory;
        $this->maxrequests = $maxrequests;
        $this->reader = $reader;
        $this->handler = $handler;
        $this->timeout = $timeout;
        $this->id = getmypid();
    }

    /**
     * @return Awaitable
     */
    public function start(): Awaitable
    {
        $tasks = [];
        while (!$this->abort()) {
            $seq = $this->reqCount();
            $conn = $this->server->accept();
            $this->logger->info('ACCEPTED', [$this->id]);
            $ctx = Context::current()->withCancel($this->cancellation);
            if ($this->timeout !== null) {
                $ctx = $ctx->withTimeout($this->timeout);
            }
            $tasks[$seq] = Task::asyncWithContext($ctx, function() use($conn) {
                try {
                    $this->handle($conn);
                } catch (\Throwable $e) {
                    $this->logger->emergency($e->getMessage(), [$this->id, $e->getFile(), $e->getLine()]);
                }
            }, static function() use($tasks, $seq) { unset($tasks[$seq]); });
        }

        /** @var Deferred $deffered */
        return Deferred::combine($tasks, function (Deferred $defer, bool $last, $index, ?\Throwable $e, $v) {
            if ($last) {
                $defer->resolve($v);
            }
        });
    }

    /**
     * @param TcpSocket|SocketStream $conn
     *
     * @return void
     */
    public function handle(SocketStream $conn): void
    {
        $conn->setOption(TcpSocket::KEEPALIVE, 0);
        $conn->setOption(TcpSocket::NODELAY, true);

        try {
            $request = $this->reader->read($conn);
            $this->logger->debug('REQUEST READED', [$this->id]);
            $response = new ResponseWriter($conn->getWritableStream(), $request->getProtocolVersion());
        } catch (TransportException $e) {
            $this->logger->error($e->getMessage(), [$this->id]);
            if ($conn->isAlive() === false) return;

            $conn->write(\sprintf("HTTP/1.1 %s %s\r\n", $e->getCode(), $e->getMessage()));
            switch(true) {
                case $e instanceof UpgradeRequiredException:
                    $conn->write(\sprintf("Upgrade: %s\r\n", $e->getRequestedVersion()));
                    break;
            }

            $conn->flush();
            $conn->close();

            return;
        } catch (\Exception $e) {
            $this->logger->alert($e->getMessage(), [$this->id]);
            return;
        }

        try {
            ($this->handler)($request, $response);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), [$e->getFile(), $e->getLine()]);
            $response->withStatus(500);
            $conn->write("\r\n");
            $conn->write("\r\n");
            $conn->writeAsync($e->getMessage());
        } finally {
            $conn->flush();
            $conn->close();
        }
    }

    /**
     * @return int
     */
    private function reqCount(): int
    {
        if ($this->requests === PHP_INT_MAX) {
            $this->requests = 0;
        }

        return $this->requests++;
    }

    /**
     * @return bool
     */
    private function abort(): bool
    {
        if($this->maxmemory && memory_get_usage(true) >= $this->maxmemory) return true;
        if($this->maxrequests && $this->reqCount() >= $this->maxrequests) return true;

        return false;
    }
}
