<?php

namespace Riftfox\HttpClient\Tests\Unit;

use Codeception\Test\Unit;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Riftfox\HttpClient\HttpClient;
use Riftfox\HttpClient\ResultFactoryInterface;
use Riftfox\HttpClient\ResultInterface;

class HttpClientTest extends Unit
{
    public function testSendDelegatesToPsrClientAndFactory()
    {
        // 1. 准备 Mock 对象
        // 模拟 PSR-18 HTTP Client
        $psrClient = $this->createMock(PsrClientInterface::class);
        // 模拟 PSR-7 Request 和 Response
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        // 模拟你的 ResultFactory
        $resultFactory = $this->createMock(ResultFactoryInterface::class);
        // 模拟预期的 Result 对象
        $expectedResult = $this->createMock(ResultInterface::class);

        // 2. 设置预期行为
        // 预期底层 client 的 sendRequest 方法会被调用一次，参数是 $request，并返回 $response
        $psrClient->expects($this->once())
            ->method('sendRequest')
            ->with($request)
            ->willReturn($response);

        // 预期 factory 的 create 方法会被调用一次，参数是 $response，并返回 $expectedResult
        $resultFactory->expects($this->once())
            ->method('create')
            ->with($response)
            ->willReturn($expectedResult);

        // 3. 实例化被测对象 (SUT) 并执行
        $client = new HttpClient($psrClient);
        $actualResult = $client->send($request, $resultFactory);

        // 4. 断言返回结果与预期一致
        $this->assertSame($expectedResult, $actualResult);
    }
}
