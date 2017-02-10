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



namespace Antares\Tester\Http\Presenters\Tests;

use Mockery as m;
use Antares\Tester\Http\Presenters\CollectivePresenter as Stub;
use Antares\Testing\TestCase;

class CollectivePresenterTest extends TestCase
{

    /**
     * constructing
     * 
     * @test
     */
    public function testConstruct()
    {
        $roundRobin = m::mock('\Antares\Tester\Builder\RoundRobin');
        $factory    = m::mock('\Antares\Contracts\Html\Form\Factory');
        $this->assertInstanceOf('Antares\Tester\Http\Presenters\CollectivePresenter', new Stub($roundRobin, $factory));
    }

    /**
     * creating instance of dynamic form
     * 
     * @test
     */
    public function form()
    {
        $roundRobin     = m::mock('\Antares\Tester\Builder\RoundRobin');
        $roundRobin->shouldReceive('build')->withNoArgs()->once()->andReturnSelf();
        $factory        = m::mock('\Antares\Contracts\Html\Form\Factory');
        $factory2       = m::mock('\Antares\Contracts\Html\Form\Factory');
        $fluent         = m::mock('\Illuminate\Support\Fluent');
        $factory2->grid = $fluent;
        $factory->shouldReceive('of')
                ->with(m::type('String'), m::type('Closure'))
                ->andReturn($factory2);

        $stub     = new Stub($roundRobin, $factory);
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
                    'username'       => 'lukasz.cirut@inbs.software',
                    'api_access_key' => 'dsfsdfdf',
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
        $this->assertInstanceOf(get_class($factory2), $stub->form());
    }

}
