<?php

use Authority\Challenge;

class ChallengeTest extends PHPUnit_Framework_TestCase
{
    public function testCanResolveResourcePairFromPair()
    {
        $mock      = new stdClass;
        $challenge = new Challenge('read', 'stdClass', $mock);
        $pair      = $challenge->getResourcePair('stdClass', $mock);

        $this->assertEquals(['stdClass', $mock], $pair);
    }

    public function testCanResolveResourcePairFromObject()
    {
        $mock      = new stdClass;
        $challenge = new Challenge('read', 'stdClass', $mock);
        $pair      = $challenge->getResourcePair();

        $this->assertEquals(['stdClass', $mock], $pair);
    }

    public function testCanResolveResourcePairFromName()
    {
        $challenge = new Challenge('read', 'DummyClass');
        $pair      = $challenge->getResourcePair();

        $this->assertEquals(['DummyClass', null], $pair);
    }
}
