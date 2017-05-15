<?php

require_once __DIR__.'/../src/Access/Access.php';
require_once __DIR__.'/Policies/UserIsLoggedInPolicy.php';

use Components\Access\Access;

class NopeSorryException extends \Exception {}

class AccessTest extends PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $access = new Access;

        $userIsLoggedInPolicy = new UserIsLoggedInPolicy;

        $access->addPolicy($userIsLoggedInPolicy);

        $access->setDenial(function($policy) {
            throw new NopeSorryException('Nope. Sorry!');
        });

        $this->access = $access;
    }

    public function accessProvider()
    {
        return [
            [
                'policy' => 'user is logged in',
                'cookie' => [],
                false
            ],
            [
                'policy' => 'user is logged in',
                'cookie' => ['user_id' => 1],
                true
            ]
        ];
    }

    /**
     * @dataProvider accessProvider
     */
    public function testInsist($policy,$cookie,$access)
    {
        if (!$access) {
            $this->expectException(NopeSorryException::class);
        }

        $result = $this->access->insist($policy,[$cookie]);

        $this->assertTrue($result);
    }

    /**
     * @dataProvider accessProvider
     */
    public function testCheck($policy,$cookie,$access)
    {
        $this->assertEquals($access,$this->access->check($policy,[$cookie]));
    }
}
