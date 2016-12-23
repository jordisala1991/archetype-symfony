<?php

namespace Archetype\DemoBundle\Tests\Unit;

use Archetype\DemoBundle\Controller\DemoController;
use Prophecy\Argument;

class DemoControllerTest extends \PHPUnit_Framework_TestCase
{
    const INDEX_VIEW = 'pages/home.html.twig';
    const MODEL = 'model';

    protected function setUp()
    {
        $this->renderer = $this->prophesize('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->service = $this->prophesize('Archetype\DemoBundle\Service\DemoService');

        $this->controller = new DemoController(
            $this->renderer->reveal(),
            $this->service->reveal()
        );
    }

    /**
     * @test
     */
    public function renderIndex()
    {
        $expected_response = new \stdClass();
        $expected_model = new \stdClass();

        $this->service->getDemoViewModel()
            ->willReturn($expected_model);

        $this->renderer->renderResponse(self::INDEX_VIEW, Argument::type('array'), null)
            ->willReturn($expected_response);

        $response = $this->controller->index();

        $this->assertSame($expected_response, $response);
    }
}