<?php declare(strict_types=1);

namespace HTTPHP\Symfony\Handler;

use HTTPHP\Handler\RequestHandlerInterface;
use HTTPHP\Symfony\Transport\RequestFactory;
use HTTPHP\Transport\ResponseWriterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class RequestHandler implements RequestHandlerInterface
{
    /**
     * @var RequestFactory
     */
    private $factory;
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * RequestHandler constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->factory = new RequestFactory();
        $this->kernel = $kernel;
    }

    /**
     * @param RequestInterface        $request
     * @param ResponseWriterInterface $writer
     *
     * @return ResponseInterface|null
     */
    public function __invoke(RequestInterface $request, ResponseWriterInterface $writer): ?ResponseInterface
    {
        $symfonyRequest = $this->factory->createRequest($request);
        try {
            $response = $this->kernel->handle($symfonyRequest);
            $this->kernel->terminate($symfonyRequest, $response);
            $writer->withStatus($response->getStatusCode());

            foreach ($response->headers->all() as $k => $v) {
                $writer->writeHeader($k, $v);
            }

            $content = $response->getContent();

            $writer->writeHeader('Content-Length', [strlen($content)]);
            $writer->writeBody($content);

            return null;
        } catch (\Throwable $e) {
            $writer->withStatus(500);
            $writer->writeBody($e->getMessage());
        }

        return null;
    }
}
