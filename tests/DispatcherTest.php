<?php

use Mockery as m;
use Authority\Events\Dispatcher;
use Authority\Authority;
use Illuminate\Container;

class DispatcherTest extends PHPUnit_Framework_Testcase
{
    public function setUp()
    {
        $this->dispatcher = new Dispatcher(m::mock('Illuminate\\Container'));
    }

    public function tearDown()
    {
        m::close();
    }

    public function testInitializeEvent()
    {
        $test = new stdClass;
        $user = new stdClass;
        $user->name = 'Tester';

        $this->dispatcher->listen('authority.initialized',function($payload) use (&$test) {
            $test->user = $payload->user; // Not sure this will be sweet
            $test->timestamp = $payload->timestamp; // Not sure this will be sweet
        });

        $authority = new Authority($user, $this->dispatcher);

        $this->assertSame($test->user, $user);
        $this->assertInstanceOf('DateTime', $test->timestamp);
    }
}
