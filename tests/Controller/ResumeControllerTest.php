<?php

namespace Tests\Controller;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Resumenter\Controller\ResumeController;
use Resumenter\Exception\NotFoundException;
use Twig\Environment;

class ResumeControllerTest extends TestCase
{
    private Environment $twig;
    private string $template = 'resume.html.twig';

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $_ENV['DATA_PATH'] = '/tests/data';
        $this->twig = $this->createMock(Environment::class);
    }

    /**
     * @throws NotFoundException
     */
    public function testRenderIndexReturnsRenderedTemplate(): void
    {
        $this->twig->expects($this->once())
            ->method('render')
            ->with('index.html.twig', $this->arrayHasKey('jobs'))
            ->willReturn('rendered index');

        $controller = new ResumeController($this->twig, $this->template, ['job1']);
        $result = $controller->render('');
        $this->assertEquals('rendered index', $result);
    }

    /**
     * @throws NotFoundException
     */
    public function testRenderResumeWithArgumentsReturnsRenderedTemplate(): void
    {
        $this->twig->expects($this->once())
            ->method('render')
            ->with($this->template, $this->callback(function ($context) {
                return isset($context['cv']) &&
                    $context['cv']['courses']['items'][0]['date'] === '01.01.2023';
            }))
            ->willReturn('rendered resume');

        $controller = new ResumeController($this->twig, $this->template, ['developer']);

        $arguments = [
            'courses' => [
                'items' => [
                    ['date' => '01.01.2021'],
                    ['date' => '01.01.2023'],
                    ['date' => '01.01.2022'],
                ]
            ]
        ];

        $result = $controller->render('developer', $arguments);
        $this->assertEquals('rendered resume', $result);
    }

    public function testRenderThrowsNotFoundException(): void
    {
        $controller = new ResumeController($this->twig, $this->template, ['developer']);

        $this->expectException(NotFoundException::class);
        $controller->render('unknown-job');
    }

    /**
     * @throws Exception
     */
    public function testSortCoursesSortsDescending(): void
    {
        $twigStub = $this->createStub(Environment::class);
        $controller = new ResumeController($twigStub);

        $data = [
            'courses' => [
                'items' => [
                    ['date' => '01.01.2020'],
                    ['date' => '01.01.2022'],
                    ['date' => '01.01.2021'],
                ]
            ]
        ];

        $controller->sortCourses($data);

        $this->assertSame('01.01.2022', $data['courses']['items'][0]['date']);
        $this->assertSame('01.01.2021', $data['courses']['items'][1]['date']);
        $this->assertSame('01.01.2020', $data['courses']['items'][2]['date']);
    }
}
