<?php declare(strict_types=1);

namespace App\Events;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Stopwatch\Stopwatch;

class TerminateListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Stopwatch
     */
    private $stopwatch;

    public function __construct(EntityManagerInterface $entityManager, Stopwatch $stopwatch)
    {
        $this->entityManager = $entityManager;
        $this->stopwatch = $stopwatch;
    }

    public function onKernelTerminate()
    {
        $this->entityManager->clear();
        $this->stopwatch->reset();
    }
}
