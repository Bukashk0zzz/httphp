<?php declare(strict_types=1);

namespace HTTPHP\Tests\Server;

use Concurrent\Network\Pipe;
use Concurrent\Task;
use HTTPHP\Transport\RequestReader;
use PHPUnit\Framework\TestCase;

class RequestReaderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testReadGet()
    {
        $file = sprintf('%s/async-test.sock', sys_get_temp_dir());
        touch($file);
        chmod($file, 0777);

        [$pipe1, $pipe2] = Pipe::pair();
        $task = Task::async(function () use($pipe2) {
            $reader = new RequestReader();
            $request = $reader->read($pipe2);
            $this->assertEquals('/concurrent-php/ext-async/tree/master/examples/tcp', $request->getUri());
            $this->assertTrue($request->hasHeader('Upgrade-Insecure-Requests'));
            $this->assertEquals('1', $request->getHeader('Upgrade-Insecure-Requests')[0]);
        });

        (new TestClient())->write($pipe1);
        $pipe1->flush();

        $a = 1;

        Task::await($task);
    }

    public function testPayloadTooLarge()
    {
        $file = sprintf('%s/async-test.sock', sys_get_temp_dir());
        touch($file);
        chmod($file, 0777);

        [$pipe1, $pipe2] = Pipe::pair();
        $task = Task::async(function () use($pipe2) {
            $reader = new RequestReader();
            $request = $reader->read($pipe2);
            $this->assertEquals('/concurrent-php/ext-async/tree/master/examples/tcp', $request->getUri());
            $this->assertTrue($request->hasHeader('Upgrade-Insecure-Requests'));
            $this->assertEquals('1', $request->getHeader('Upgrade-Insecure-Requests')[0]);
        });

        (new TestClient())->write($pipe1);
        $pipe1->flush();
        $pipe1->close();

        Task::await($task);
    }

    public function testReadPost()
    {
        self::markTestIncomplete('Need to be fixed');

        $file = sprintf('%s/async-test.sock', sys_get_temp_dir());
        touch($file);
        chmod($file, 0777);

        [$pipe1, $pipe2] = Pipe::pair();
        $task = Task::async(function () use($pipe2) {
            $reader = new RequestReader();
            $request = $reader->read($pipe2);
            $this->assertEquals('BODY_DATA', (string) $request->getBody());
        });

        $pipe1->write("PORT /concurrent-php/ext-async/tree/master/examples/tcp HTTP/1.1\r\n");
        $pipe1->write("Host: github.com\r\n");
        $pipe1->write("Connection: keep-alive\r\n");
        $pipe1->write("Cache-Control: max-age=0\r\n");
        $pipe1->write("Upgrade-Insecure-Requests: 1\r\n");
        $pipe1->write("User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36 OPR/58.0.3135.132\r\n");
        $pipe1->write("Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8\r\n");
        $pipe1->write("Accept-Encoding: gzip, deflate, br\r\n");
        $pipe1->write("Accept-Language: en-US,en;q=0.9,ru;q=0.8,uk;q=0.7\r\n");
        $pipe1->write("\r\n");
        $pipe1->write("BODY_DATA");
        $pipe1->flush();
        $pipe1->close();

        Task::await($task);
    }

    public function testReadNewLine()
    {
        $chunks = ["one\r", "\ntwo", "\r\n\r\nth\rree\r\n", "", ""];

        $buffer = $line = null;
        $linesFound = [];
        foreach ($chunks as $chunk) {
            $buffer .= $chunk;
            $result = $this->readLine($buffer, $line);
            $linesFound[] = [
                'text' => $line,
                'result' => $result,
            ];
        }

        self::assertEquals($linesFound, [
            [
                'text' => null,
                'result' => false,
            ],
            [
                'text' => 'one',
                'result' => true,
            ],
            [
                'text' => 'two',
                'result' => true,
            ],
            [
                'text' => '',
                'result' => true,
            ],
            [
                'text' => 'three',
                'result' => true,
            ],
        ]);
    }

    private function readLine(string &$buffer, ?string &$result = null): bool
    {
        $carry = '';
        foreach (str_split($buffer) as $k => $v) {
            if ($v === "\n") {
                $result = $carry;
                $buffer = substr($buffer, $k + 1);

                return true;
            }
            if ($v === "\r") continue;
            $carry .= $v;
        }

        $buffer = $carry;

        return false;
    }


}
