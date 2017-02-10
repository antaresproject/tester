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



namespace Antares\Tester\Processor\Tests;

use Mockery as m;
use Antares\Tester\Processor\CollectiveProcessor as Stub;
use Antares\Tester\Contracts\Tester;
use Antares\Tester\Adapter\ResponseAdapter;
use Antares\Testing\TestCase;

class CollectiveProcessorTest extends TestCase
{

    /**
     * @var \Antares\Tester\Http\Processors\CollectiveProcessor
     */
    private $stub;

    /**
     * @see inherit
     */
    public function setUp()
    {
        parent::setUp();
        $presenter = m::mock('\Antares\Tester\Http\Presenters\CollectivePresenter');


        $this->stub = new Stub($presenter);
    }

    /**
     * test constructing
     * 
     * @test
     */
    public function testConstruct()
    {
        $this->assertSame(get_class($this->stub), 'Antares\Tester\Processor\CollectiveProcessor');
    }

    /**
     * test shows method without ajax
     * 
     * @test
     */
    public function testIndex()
    {
        $facade      = m::mock('\Antares\Tester\Facade\ModuleFacade');
        $presenter   = m::mock('\Antares\Tester\Http\Presenters\CollectivePresenter');
        $filesystem  = m::mock('\Illuminate\Filesystem\Filesystem');
        $formBuilder = m::mock('\Antares\Html\Form\FormBuilder');
        $presenter->shouldReceive('form')->withAnyArgs()->andReturn($formBuilder);

        $stub       = new Stub($presenter, $filesystem, $facade);
        $controller = m::mock('Antares\Tester\Http\Controllers\Admin\CollectiveController');
        $view       = m::mock('Illuminate\View\View');
        $controller->shouldReceive('show')->with(m::type('array'))->andReturn($view);

        $this->assertInstanceOf(get_class($view), $stub->index($controller));
    }

    /**
     * test show with ajax
     * 
     * @test
     */
    public function testPrepare()
    {
        $data     = $this->stub->prepare([])->getData();
        $this->assertTrue(isset($data[0]->error));
        $this->assertSame("Unable to start tests. No module has been selected.", $data[0]->error);
        $memory   = m::mock('\Antares\Tester\Memory\Handler');
        $active   = [
            'domains/dns' => [
                'path'        => 'vendor::antares/modules/domains/dns',
                'source-path' => 'vendor::antares/modules/domains/dns',
                'name'        => 'domains/dns',
                'full_name'   => 'Dns Manager Module',
                'description' => 'Foo',
                'author'      => 'Foo Foo',
                'url'         => 'https://billevo.com/docs/dns',
                'version'     => '1.0.0',
                'config'      => [],
                'autoload'    => [],
                'provides'    => [
                    'Antares\Domains\Dns\DnsServiceProvider'
                ]
            ]
        ];
        $provider = m::mock('Antares\Memory\Provider');
        $tests    = [
            'Rackspace Module Configuration Test' =>
            [
                'component_id' => 12,
                'component'    => 'domains/dns',
                'controls'     =>
                [
                    'username'       => 'lukasz.cirut@gmail.com',
                    'api_access_key' => 'myszka',
                    'access_key'     => 'testowanie',
                    'hostname'       => '123.123.123.123',
                    'ssl'            => 'on',
                    'default_ip'     => '123.123.123.123',
                    'create_zones'   => 'on'
                ],
                'name'         => 'Rackspace',
                'title'        => 'Rackspace Module Configuration Test',
                'validator'    => 'Antares\Domains\Dns\Tester\RackspaceTester',
                'executor'     => 'Antares\Domains\Dns\Http\Forms\RackspaceForm',
                'id'           => 15
            ]
        ];

        $provider->shouldReceive('all')->withNoArgs()->andReturn($tests);
        $provider->shouldReceive('finish')->withNoArgs()->andReturn($tests);
        $memory->shouldReceive('get')->with('extensions.active')->andReturn($active);
        $memory->shouldReceive('make')->with('tests')->andReturn($provider);

        $this->app['antares.memory'] = $memory;

        $data = $this->stub->prepare(['module' => [15 => 'foo', 1 => 'foo2']])->getData();


        $this->assertSame(sprintf("Currently testing %s  in %s.", 'Rackspace Module Configuration Test', 'Dns Manager Module'), $data[0]->message);
    }

    /**
     * test running method
     * 
     * @test
     */
    public function testRun()
    {
        $presenter = m::mock('\Antares\Tester\Http\Presenters\CollectivePresenter');
        $stub      = new Stub($presenter);

        $listener = m::mock('Antares\Tester\Http\Controllers\Admin\CollectiveController');
        $listener->shouldReceive('render')->with(m::type('Illuminate\View\View'))->andReturn(true);
        $view     = m::mock('Illuminate\View\View');
        $listener->shouldReceive('show')->with(m::type('array'))->andReturn($view);

        $viewFactory                                    = m::mock('Illuminate\View\View');
        $viewFactory
                ->shouldReceive('with')
                ->withAnyArgs()
                ->once()
                ->andReturnSelf()
                ->shouldReceive('make')
                ->with('antares/tester::admin.partials._error', [], [])
                ->once()
                ->andReturnSelf();
        $this->app['Illuminate\Contracts\View\Factory'] = $viewFactory;
        $this->assertTrue($stub->run($listener, [
                    'validator' => __NAMESPACE__ . '\\Foo'
        ]));
    }

}

class Foo extends ResponseAdapter implements Tester
{

    public function __invoke(array $data = null)
    {
        return $this;
    }

}
