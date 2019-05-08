<?php declare(strict_types=1);

namespace HTTPHP\Tests\Server;

use Concurrent\Stream\WritableStream;

class TestClient
{
    public function write(WritableStream $stream)
    {
        $stream->write("GET /concurrent-php/ext-async/tree/master/examples/tcp HTTP/1.1\r\n");
        $stream->write("Host: github.com\r\n");
        $stream->write("Connection: keep-alive\r\n");
        $stream->write("Cache-Control: max-age=0\r\n");
        $stream->write("Upgrade-Insecure-Requests: 1\r\n");
        $stream->write("User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36 OPR/58.0.3135.132\r\n");
        $stream->write("Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8\r\n");
        $stream->write("Accept-Encoding: gzip, deflate, br\r\n");
        $stream->write("Accept-Language: en-US,en;q=0.9,ru;q=0.8,uk;q=0.7\r\n");
        $stream->write("\r\n");
    }

    public function writeCorrupted(WritableStream $stream)
    {
        $stream->write("POST /concurrent-php/ext-async/tree/master/examples/tcp HTTP/1.1\r\n");
        $stream->write("Host: github.com\r\n");
        $stream->write("Connection: keep-alive\r\n");
        $stream->write("Cache-Control: max-age=0\r\n");
        $stream->write("Upgrade-Insecure-Requests: 1\r\n");
        $stream->write("User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36 OPR/58.0.3135.132\r\n");
        $stream->write("Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8\r\n");
        $stream->write("Accept-Encoding: gzip, deflate, br\r\n");
        $stream->write("Accept-Language: en-US,en;q=0.9,ru;q=0.8,uk;q=0.7");
    }
}
