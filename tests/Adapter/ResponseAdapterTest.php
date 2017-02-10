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

use Antares\Tester\Adapter\ResponseAdapter as Stub;
use Mockery as m;
use Antares\Testing\TestCase;

class ResponseAdapterTest extends TestCase
{

    protected static $config = [
        'default' => [
            'codes'        => [
                'UNSUPPORTED_PROTOCOL' => 1
            ],
            'descriptions' => [
                1 => "Unsupported protocol."
            ]
        ]
    ];

    /**
     * @inherits
     */
    public function setUp()
    {
        parent::setUp();
        $repository          = m::mock('\Illuminate\Contracts\Config\Repository');
        $repository->shouldReceive('get')->with('antares/tester::codes.errors')->andReturn(self::$config);
        $this->app['config'] = $repository;
    }

    /**
     * Test Antares\Tester\Adapter\ResponseAdapter::__construct() method.
     *
     * @test
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('Antares\Tester\Adapter\ResponseAdapter', new Stub);
    }

    /**
     * Test Antares\Tester\Adapter\ResponseAdapter::getResponse() method.
     *
     * @test
     */
    public function testGetResponse()
    {
        $stub       = new Stub;
        $this->assertEmpty($stub->getResponse());
        $descriptor = 'UNSUPPORTED_PROTOCOL';
        $stub->setError($descriptor);
        $expected   = [
            [
                'message'    => "Unsupported protocol.",
                'code'       => 1,
                'type'       => "error",
                'descriptor' => $descriptor
            ]
        ];
        $this->assertEquals($expected, $stub->getResponse());
    }

    /**
     * Test Antares\Tester\Adapter\ResponseAdapter::setError() method.
     *
     * @test
     */
    public function testSetError()
    {
        $stub       = new Stub;
        $descriptor = 'UNSUPPORTED_PROTOCOL';
        $this->assertInstanceOf('Antares\Tester\Adapter\ResponseAdapter', $stub->setError($descriptor));
    }

}
