<?php declare(strict_types=1);

namespace HTTPHP\Symfony\Command;

use HTTPHP\Server;
use HTTPHP\Symfony\Handler\RequestHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class HttpCommand extends Command
{
    protected static $defaultName = 'http:serve';
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var HttpKernelInterface
     */
    private $kernel;

    /**
     * HttpServeCommand constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, HttpKernelInterface $kernel)
    {
        parent::__construct();
        $this->logger = $logger;
        $this->kernel = $kernel;
    }

    protected function configure()
    {
        $this
            ->setDescription('Start HTTP server')
            ->addOption('address', 'a', InputOption::VALUE_OPTIONAL, 'TCP address to listen on', '127.0.0.1')
            ->addOption('port', 'p', InputOption::VALUE_OPTIONAL, 'TCP port to listen on', 3000)
            //->addOption('cluster', null, InputOption::VALUE_NONE, 'Enable cluster mode', 3000)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server = new Server(new RequestHandler($this->kernel), $this->logger);
        $server->listen($input->getOption('address'), (int) $input->getOption('port'));
    }
}
