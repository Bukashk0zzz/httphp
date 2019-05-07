<?php declare(strict_types=1);

namespace HTTPHP\Transport;

use Amp\Http\InvalidHeaderException;
use Amp\Http\Rfc7230;
use Concurrent\Network\SocketStream;
use GuzzleHttp\Psr7\Request;
use HTTPHP\Transport\Exception\BadRequestException;
use HTTPHP\Transport\Exception\PayloadTooLargeException;
use HTTPHP\Transport\Exception\UpgradeRequiredException;
use HTTPHP\Transport\Exception\VersionNotSupportedException;
use Psr\Http\Message\RequestInterface;

class RequestReader implements RequestReaderInterface
{
    public const CHUNK_SIZE = 4;
    public $crlfSeq = 0;
    public $seq = 0;
    public $crlfPos = 0;
    public $maxHeaderSize = 8192; //8Kb
    public $maxBodySize = 1073741824; //1GB - Temporary limit

    public function read(SocketStream $stream): RequestInterface
    {
        $method = $target = $protocolVersion = $request = null;
        $rawHeaders = $buffer = $line = null;
        $this->crlfSeq = $this->crlfPos = $this->seq = $readed = 0;

        $chunkSize = self::CHUNK_SIZE;
        while($stream->isAlive() && null !== ($chunk = $stream->read($chunkSize))) {
            $readed += $chunkSize;
            if ($readed >= $this->maxHeaderSize) {
                throw new PayloadTooLargeException($this->maxHeaderSize);
            }

            $buffer .= $chunk;
            $lineFound = $this->readLine($buffer, $line);

            if ($lineFound || strpos($buffer, "\r") !== false) {
                $chunkSize = 1; // Drawback because rewind is not available
            } else {
                $chunkSize = self::CHUNK_SIZE;
            }

            if ($lineFound === false) {
                continue;
            }

            if ($lineFound && $method === null) {
                [$method, $target, $protocolVersion] = $this->parseStartLine((string) $line);
                $this->ensureProtocolVersionSupported($protocolVersion);
                continue;
            }

            if (!empty(trim($line))) {
                $rawHeaders .= "$line\r\n";

                if ($buffer !== "\r\n") {
                    continue;
                }
            }

            $request = new Request($method, $target, $this->parseHeaders($rawHeaders), null, $protocolVersion);
            $contentLength = $request->hasHeader('Content-Length') ?  $request->getHeader('Content-Length')[0] : null;

            if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true)) {
                if ($contentLength === null) throw new BadRequestException();
                if ($this->maxBodySize && $contentLength > $this->maxBodySize) throw new PayloadTooLargeException($this->maxBodySize);
                $request = $request->withBody(new RequestBodyReader($stream->getReadableStream(), (int) $contentLength));
            }
            break;
        }

        return $request;
    }


    private function readLine(string &$buffer, ?string &$result = null): bool
    {
        $carry = '';
        foreach (str_split($buffer) as $k => $v) {
            if ($v === "\n") {
                $result = str_replace("\r", '', $carry);
                $buffer = substr($buffer, $k + 1);

                return true;
            }
            $carry .= $v;
        }

        $buffer = $carry;

        return false;
    }
    /**
     * @param string $startLine
     *
     * @return array
     */
    private function parseStartLine(string $startLine): array
    {
        [$method, $target, $protocolVersion] = explode(' ', trim($startLine));

        return [strtoupper($method), $target, ltrim($protocolVersion, 'HTTP/')];
    }

    private function parseHeaders(?string $rawHeaders): array
    {
        if ($rawHeaders === null) {
            throw new BadRequestException();
        }

        try {
            $rawHeaders = sprintf("%s\r\n", rtrim($rawHeaders, "\r\n"));
            return Rfc7230::parseHeaders($rawHeaders);
        } catch (InvalidHeaderException $e) {
            throw new BadRequestException($rawHeaders);
        }
    }

    private function ensureProtocolVersionSupported(string $protocolVersion)
    {
        [$major, $minor] = explode('.', $protocolVersion);
        if ((int) $major > 1) throw new VersionNotSupportedException();
        if ($major === '0') throw new UpgradeRequiredException('1.1');
    }

//    private function encode(string $data): string
//    {
//        return str_replace(["\r", "\n"], ['\r', '\n'], $data);
//    }
}
