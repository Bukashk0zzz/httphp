<?php declare(strict_types=1);

namespace HTTPHP\Symfony\Transport;

use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestFactory
{
    /**
     * @param RequestInterface $req
     *
     * @return Request
     */
    public function createRequest(RequestInterface $req): Request
    {
        $query = [];
        parse_str($req->getUri()->getQuery(), $query);

        // TODO: move to separate class as it could be reused
        $server = [
            'SERVER_NAME' => 'localhost',
            'SERVER_PORT' => 3000,
            'HTTP_HOST' => $req->getHeader('Host'),
            'REMOTE_ADDR' => '127.0.0.1',
            'SERVER_PROTOCOL' => sprintf('HTTP/%s', $req->getProtocolVersion()),
            'REQUEST_TIME' => time(),
        ];

        return Request::create(
            (string) $req->getUri(),
            $req->getMethod(),
            $query,
            $req->getHeader('Cookie'),
            [], // TODO
            $server,
            $req->getBody()
        );
    }
}
