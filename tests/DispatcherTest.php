<?php

use Mockery as m;
use Authority\Events\Dispatcher;
use Authority\Authority;
use Illuminate\Container\Container;

class DispatcherTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->dispatcher = new Dispatcher(new Container);
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

        // Need to decouple test from Authority class eventually
        $this->dispatcher->listen('authority.initialized', function($payload) use (&$test) {
            $test->user = $payload->user;
            $test->timestamp = $payload->timestamp;
        });

        $authority = new Authority($user, $this->dispatcher);

        $this->assertSame($test->user, $user);
        $this->assertInstanceOf('DateTime', $test->timestamp);
    }
}
