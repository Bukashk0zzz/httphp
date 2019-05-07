<?php declare(strict_types=1);

namespace HTTPHP\Transport;

use Concurrent\Stream\StreamException;
use Concurrent\Stream\WritableStream;
use HTTPHP\RFC\RFC723x;
use HTTPHP\Transport\Exception\HeadersAlreadySentException;
use HTTPHP\Transport\Exception\TransportException;

class ResponseWriter implements ResponseWriterInterface
{
    /**
     * @var int
     */
    private $status = RFC723x::STATUS_OK;
    /**
     * @var bool
     */
    private $headersSent = false;
    /**
     * @var string
     */
    private $protocolVersion;

    /**
     * @var WritableStream
     */
    private $stream;

    /**
     * ResponseWriter constructor.
     *
     * @param WritableStream $stream
     * @param string         $protocolVersion
     */
    public function __construct(WritableStream $stream, string $protocolVersion = '1.1')
    {
        $this->stream = $stream;
        $this->protocolVersion = $protocolVersion;
    }

    /**
     * Close the stream, will fail all pending and future writes.
     *
     * @param \Throwable $e Reason for close.
     */
    public function close(?\Throwable $e = null): void
    {

        $this->stream->close($e);
    }

    /**
     * @param string $name
     * @param array  $value
     *
     * @return void
     * @throws HeadersAlreadySentException
     * @throws TransportException
     */
    public function writeHeader(string $name, array $value): void
    {
        $this->write(\sprintf("%s: %s\r\n", $name, implode(';', $value)));
    }

    /**
     * Write a chunk of data to the stream.
     */
    public function writeBody(string $data): void
    {
        $this->write("\r\n");
        $this->write($data);
    }

    /**
     * @param int $status
     *
     * @return void
     */
    public function withStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return void
     * @throws TransportException
     */
    private function writeStatusHeader()
    {
        $this->stream->write(sprintf("HTTP/%s %d %s\r\n", $this->protocolVersion, $this->status, RFC723x::REASONS[$this->status] ?? ''));
        $this->stream->write(\sprintf("Server: %s\r\n", 'HTTPHP/0.1'));
    }

    /**
     * @param string $data
     *
     * @return void
     * @throws TransportException
     */
    private function write(string $data): void
    {
        try {
            if ($this->headersSent === false) {
                $this->writeStatusHeader();
                $this->headersSent = true;
            }

            $this->stream->write($data);
        } catch (StreamException $e) {
            throw new TransportException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
