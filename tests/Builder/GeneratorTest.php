<?php

/**
 * Part of the Antares Project package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Tester
 * @version    0.9.0
 * @author     Antares Team
 * @license    BSD License (3-clause)
 * @copyright  (c) 2017, Antares Project
 * @link       http://antaresproject.io
 */



namespace Antares\Tester\Builder\Tests;

use Antares\Tester\Builder\Generator as Stub;
use Mockery as m;
use Antares\Testing\TestCase;

class GeneratorTest extends TestCase
{

    /**
     * @var \Antares\Tester\Builder\Generator 
     */
    protected $stub;

    /**
     * @inherit
     */
    public function setUp()
    {
        parent::setUp();
        $repository          = m::mock('\Illuminate\Contracts\Config\Repository');
        $repository->shouldReceive('get')->with('antares/tester::config')->andReturn([
            'memory'    => [
                'model' => '\Antares\Tester\Model\MemoryTests'
            ],
            'view'      => 'antares/tester::admin.partials._button',
            'container' => 'antares/foundation::scripts',
            'inputId'   => 'tester-button',
        ]);
        $this->app['config'] = $repository;
    }

    /**
     * Test Antares\Tester\Builder\Generator::__construct() method.
     *
     * @test
     */
    public function testConstruct()
    {
        $stub = new Stub(m::mock('\Antares\Tester\Contracts\Extractor'), m::mock('\Antares\Tester\Contracts\ClassValidator'));
        $this->assertInstanceOf('Antares\Tester\Builder\Generator', $stub);
    }

    /**
     * Test Antares\Tester\Builder\Generator::build() method.
     *
     * @test
     */
    public function testBuild()
    {
        $extractor                                      = m::mock('\Antares\Tester\Contracts\Extractor');
        $validator                                      = m::mock('\Antares\Tester\Contracts\ClassValidator');
        $attributes                                     = [
            'id'        => 'foo',
            'validator' => 'TestValidator',
            'title'     => 'Test Function'
        ];
        $extractor->shouldReceive('generateScripts')
                ->withAnyArgs()
                ->andReturnSelf();
        $validator->shouldReceive('isValid')->with($attributes)->andReturn(true);
        $stub                                           = new Stub($extractor, $validator);
        $view                                           = m::mock('Illuminate\Contracts\View\Factory');
        $expects                                        = 'result';
        $view->shouldReceive('render')->withAnyArgs()->andReturn($expects);
        $view->shouldReceive('make')->withAnyArgs()->andReturnSelf();
        $this->app['Illuminate\Contracts\View\Factory'] = $view;
        $this->assertSame($stub->build('foo', $attributes), $expects);
    }

}
