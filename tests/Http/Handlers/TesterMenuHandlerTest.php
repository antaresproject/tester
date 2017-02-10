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



namespace Antares\Tester\Http\Handlers\Tests;

use Mockery as m;
use Antares\Tester\Http\Handlers\TesterMenuHandler as Stub;
use Antares\Testing\TestCase;

class TesterMenuHandlerTest extends TestCase
{

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Check whether the menu should be displayed.
     * 
     * @test
     */
    public function testAuthorize()
    {
        $stub      = new Stub($this->app);
        $guardMock = m::mock('Antares\Contracts\Auth\Guard');
        $guardMock->shouldReceive('guest')->andReturn(false);
        $this->assertTrue($stub->authorize($guardMock));
    }

    /**
     * Create a handler.
     *
     * @test
     */
    public function testHandle()
    {
        $stub                                      = new Stub($this->app);
        $this->app['Antares\Contracts\Auth\Guard'] = $guard                                     = m::mock('Antares\Contracts\Auth\Guard');
        $guard->shouldReceive('guest')->andReturn(false);
        $this->assertNull($stub->handle());
    }

}
