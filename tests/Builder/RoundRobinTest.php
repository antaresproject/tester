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

use Antares\Tester\Builder\RoundRobin as Stub;
use Mockery as m;
use Antares\Testing\TestCase;

class RoundRobinTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        $session              = m::mock('Illuminate\Session\SessionManager');
        $session->shouldReceive('getToken')->withNoArgs()->andReturn(str_random(10));
        $this->app['session'] = $session;
    }

    /**
     * Test Antares\Tester\Builder\RoundRobin::generateScripts() method.
     *
     * @test
     */
    public function generateScripts()
    {
        $stub = new Stub;
        $this->assertTrue(str_contains($stub->generateScripts([
                            'response-container' => 'foo',
                            'form-container'     => 'foo'
                        ]), '$(document)'));
    }

    /**
     * Test Antares\Tester\Builder\RoundRobin::build() method.
     *
     * @test
     */
    public function build()
    {
        $session              = m::mock('Illuminate\Session\SessionManager');
        $session->shouldReceive('getToken')->withNoArgs()->andReturn(str_random(10));
        $this->app['session'] = $session;

        $stub = new Stub;
        $this->assertNull($stub->build('round', [
                    'response-container' => 'foo',
                    'form-container'     => 'foo'
        ]));
    }

}
