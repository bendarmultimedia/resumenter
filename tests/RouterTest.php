<?php

namespace Tests;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Resumenter\Controller\ControllerInterface;
use Resumenter\Router;

class RouterTest extends TestCase
{
    private ControllerInterface $controllerMock;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->controllerMock = $this->createMock(ControllerInterface::class);
    }

    public function testReturnsRenderedPageIfPathInRoutesAndNoGetParam(): void
    {
        $_SERVER['REQUEST_URI'] = '/developer';
        $_GET = [];

        $this->controllerMock->expects($this->once())
            ->method('render')
            ->with('developer', [])
            ->willReturn('rendered content');

        $router = new Router($this->controllerMock, ['developer']);
        $output = $router->handleRequest();

        $this->assertSame('rendered content', $output);
    }

    public function testReturns404IfPathNotInRoutesAndNoGetParam(): void
    {
        $_SERVER['REQUEST_URI'] = '/non-existent';
        $_GET = [];

        $router = new Router($this->controllerMock, ['developer']);
        $output = $router->handleRequest();

        $this->assertStringContainsString('404 Not Found', $output);
        $this->assertSame(404, http_response_code());
    }

    public function testReturns404IfGetParamNotInRoutes(): void
    {
        $_SERVER['REQUEST_URI'] = '/?job=non-existent';
        $_GET = ['non-existent'];

        $router = new Router($this->controllerMock, ['developer']);
        $output = $router->handleRequest();

        $this->assertStringContainsString('404 Not Found', $output);
    }
}
