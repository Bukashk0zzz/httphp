<?php declare(strict_types=1);

namespace HTTPHP\Transport;

use Concurrent\Network\SocketStream;
use Psr\Http\Message\RequestInterface;

interface RequestReaderInterface
{
    public function read(SocketStream $socket): RequestInterface;
}
