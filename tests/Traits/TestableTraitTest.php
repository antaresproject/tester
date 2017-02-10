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



namespace Antares\Tester\Traits\Tests;

use Mockery as m;
use Antares\Testing\TestCase;
use Antares\Tester\Traits\TestableTrait;

class TestableTraitTest extends TestCase
{

    use TestableTrait;

    /**
     * test add button method
     */
    public function testAddTestButton()
    {
        $formBuilder        = m::mock('\Antares\Html\Support\FormBuilder');
        $formBuilder->shouldReceive('tester')->with('test-button', ['executor' => get_class($this)], null)->andReturn(true);
        $attributes['form'] = $formBuilder;
        $this->assertNull($this->addTestButton('test-button', $attributes));
    }

}
