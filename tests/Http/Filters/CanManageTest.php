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



namespace Antares\Tester\Http\Filters\Tests;

use Mockery as m;
use Antares\Tester\Http\Filters\CanManage as Stub;
use Antares\Testing\TestCase;

class CanManageTest extends TestCase
{

    /**
     * testing filter method
     * 
     * @test
     */
    public function testFilter()
    {

        $foundation = m::mock('\Antares\Contracts\Foundation\Foundation');
        $auth       = m::mock('\Illuminate\Contracts\Auth\Guard');
        $config     = m::mock('\Illuminate\Contracts\Config\Repository');
        $acl        = m::mock('\Antares\Contracts\Authorization\Authorization');
        $factory1   = m::mock('\Antares\Contracts\Authorization\Factory');
        $factory1->shouldReceive('make')->andReturnSelf()
                ->shouldReceive('can')->andReturn(false);

        $route   = m::mock('\Illuminate\Routing\Route');
        $request = m::mock('\Illuminate\Http\Request');

        $foundation->shouldReceive('acl')->once()->andReturn($acl)
                ->shouldReceive('handles')->once()->with('antares::login')->andReturn('http://localhost/admin/login');
        $acl->shouldReceive('can')->once()->with('manage-antares')->andReturn(false);
        $auth->shouldReceive('guest')->once()->andReturn(true);
        $config->shouldReceive('get')->once()->with('antares/foundation::routes.guest')->andReturn('antares::login');

        $stub1 = new Stub($foundation, $factory1);
        $this->assertInstanceOf('\Illuminate\Http\RedirectResponse', $stub1->filter($route, $request, 'tools-tester'));

        $factory2 = m::mock('\Antares\Contracts\Authorization\Factory');
        $factory2->shouldReceive('make')
                ->andReturnSelf()
                ->shouldReceive('can')
                ->andReturn(true);

        $stub2 = new Stub($foundation, $factory2);
        $this->assertNull($stub2->filter($route, $request, 'tools-tester'));
    }

}
