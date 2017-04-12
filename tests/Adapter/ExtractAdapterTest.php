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

namespace Antares\Tester\Adapter\Tests;

use Antares\Testing\TestCase;
use Antares\Tester\Adapter\ExtractAdapter as Stub;
use Mockery as m;

class ExtractAdapterTest extends TestCase
{

    /**
     * Test Antares\Tester\Adapter\ExtractAdapter::generateScripts() method.
     *
     * @test
     */
    public function testGenerateScripts()
    {
        $config               = m::mock('\Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')->with('antares/tester::container')->andReturn('test-container');
        $session              = m::mock('Illuminate\Session\SessionManager');
        $session->shouldReceive('token')->withNoArgs()->andReturn(str_random(10));
        $this->app['config']  = $config;
        $this->app['session'] = $session;
        $stub                 = new Stub();
        $this->assertNull($stub->generateScripts(['id' => 'test-form']));
        $this->assertTrue(str_contains(app('antares.asset')->container('test-container')->inline(), 'text/javascript'));
    }

    /**
     * Test Antares\Tester\Adapter\ExtractAdapter::extractForm() method.
     *
     * @test
     */
    public function testExecptionThrowsWhenExtractForm()
    {
        $stub = new Stub();

        try {
            $stub->extractForm('Antares\Tester\Adapter\Tests\ExtractAdapterTest');
        } catch (\Exception $e) {
            $this->assertSame($e->getMessage(), 'Undefined offset: 1');
            $this->assertEquals(0, $e->getCode());
        }
    }

}
