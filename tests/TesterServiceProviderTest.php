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



namespace Antares\Tester\Tests;

use Mockery as m;
use Antares\Tester\TesterServiceProvider as Stub;
use Illuminate\Support\Facades\App;
use Antares\Testing\TestCase;

class TesterServiceProviderTest extends TestCase
{

    /**
     * test checks whether regster method binds valid elements
     * 
     * @test
     */
    public function testRegisterMethod()
    {

        $app = $this->app;

        $presenter = m::mock('\Antares\Foundation\Http\Presenters\Extension');
        $validator = m::mock('\Antares\Foundation\Validation\Extension');

        App::instance('Antares\Foundation\Http\Presenters\Extension', $presenter);
        App::instance('Antares\Foundation\Validation\Extension', $validator);

        $app['config'] = $config        = m::mock('\Illuminate\Contracts\Config\Repository');

        $app['events'] = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $app['files']  = m::mock('\Illuminate\Filesystem\Filesystem');

        $stub = new Stub($app);
        $stub->register();
        $this->assertInstanceOf('\Antares\Tester\Factory', app('antares.tester'));
    }

    /**
     * Test TesterServiceProvider::boot() method.
     *
     * @test
     */
    public function testThrowExceptionWhenBootMethodAndInvalidMock()
    {

        $path              = realpath(__DIR__ . '/../');
        $app               = [
            'router' => $router  = m::mock('\Illuminate\Routing\Router'),
        ];
        $app['view.paths'] = array($path);
        $config            = m::mock('\Antares\Config\Repository');
        $config->shouldReceive('package')->with('antares/tester', "{$path}/resources/config", 'antares/tester')->andReturnNull();


        $config->shouldReceive('offsetGet')
                ->andReturnUsing(function ($c) {
                    array(realpath(__DIR__ . '/../'));
                });
        $app['config'] = $config;
        $stub          = new Stub($app);
        try {
            $stub->boot();
        } catch (\Exception $e) {
            $this->assertTrue(strpos($e->getMessage(), "antares.acl") !== false);
        }
    }

}
