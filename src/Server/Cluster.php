<?php declare(strict_types=1);

namespace HTTPHP;

use Concurrent\Context;
use Concurrent\Network\TcpServer;
use Concurrent\Process\Process;
use Concurrent\Process\ProcessBuilder;
use Concurrent\SignalWatcher;
use Concurrent\Task;
use Concurrent\Timer;
use HTTPHP\Handler\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class Cluster extends Server
{
    /**
     * @var int|null
     */
    private $workersNum;
    /**
     * @var int
     */
    private $supervisorTick = 2;

    /**
     * @var Process[]
     */
    private $workers = [];

    /**
     * Cluster constructor.
     *
     * @param RequestHandlerInterface $handler
     * @param LoggerInterface|null    $logger
     * @param int|null                $workersNum
     * @param int|null                $maxmemory
     * @param int|null                $maxrequests
     */
    public function __construct(RequestHandlerInterface $handler, ?LoggerInterface $logger = null, ?int $workersNum = 1, ?int $maxmemory = null, ?int $maxrequests = null)
    {
        parent::__construct($handler, $logger, $maxmemory, $maxrequests);
        $this->workersNum = $workersNum;
    }

    /**
     * @param string $address
     * @param int    $port
     *
     * @return void
     */
    public function listen(string $address = '0.0.0.0', int $port = 3000): void
    {
        $this->registerSignalHandler();
        try {
            if (Process::isForked()) {
                $ipc = Process::forked();
                $this->server = TcpServer::import($ipc);
                $this->startWorker();
                $this->shutdown();
            } else {
                $this->registerSignalHandler();
                $this->createServer($address, $port);
                $this->supervise();

            }
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            $this->shutdown();
        }
    }

    /**
     * @return void
     */
    public function shutdown(): void
    {
        foreach ($this->workers as $worker) {
            $worker['process']->signal(SignalWatcher::SIGINT);
        }
        parent::shutdown();
    }


    /**
     * @return void
     */
    private function supervise(): void
    {
        while(!$this->shutdown) {
            $timer = new Timer($this->supervisorTick);
            $timer->awaitTimeout();

            foreach ($this->workers as $pid => $worker) {
                if (!$worker->isRunning()) {
                    unset($this->workers[$pid]);
                }
            }

            while (count($this->workers) < $this->workersNum) {
                $this->spawn();
            }
        }
    }

    /**
     * @return void
     */
    private function spawn(): void
    {
        $builder = ProcessBuilder::fork($_SERVER['PHP_SELF']);
        $process = $builder->start(...array_slice($_SERVER['argv'], 1));

        $this->server->export($process->getIpc());
        $this->logger->debug('Worker forked', ['pid' => $process->getPid()]);
        $this->workers[$process->getPid()] = ['process' => $process];

        $this->workers[$process->getPid()]['task'] = Task::asyncWithContext(Context::current()->withCancel($this->cancellation), function() use($process) {
            $process->join();
        });
    }
}
