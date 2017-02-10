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



namespace Antares\Tester\Validator\Tests;

use Mockery as m;
use Antares\Tester\Validator\ClassValidator as Stub;
use Antares\Testing\TestCase;

class ClassValidatorTest extends TestCase
{

    /**
     * test isValid method
     */
    public function testIsValid()
    {
        $validator  = m::mock('\Antares\Tester\Contracts\Tester');
        $validator->shouldReceive('__invoke')->withAnyArgs()->andReturnSelf();
        $attributes = [
            'validator' => $validator
        ];
        $stub       = new Stub();
        $this->assertTrue($stub->isValid($attributes));
    }

}
