<?php

use Mockery as m;
use Authority\Rule;

class RuleTest extends PHPUnit_Framework_Testcase
{
    public function setUp()
    {
        $this->rule = new Rule(true, 'read', m::mock('Obj'));
    }

    public function testCanBeSetToAllowOrDeny()
    {
        $allowed_rule = new Rule(true, 'read', m::mock('Obj'));
        $denied_rule = new Rule(false, 'write', m::mock('Obj'));
        $this->assertTrue($allowed_rule->getBehavior());
        $this->assertFalse($denied_rule->getBehavior());
    }

    public function testCanMatchAction()
    {
        $this->assertTrue($this->rule->matchesAction('read'));
        $this->assertFalse($this->rule->matchesAction('write'));
    }

    public function testCanMatchResource()
    {
        $this->assertTrue($this->rule->matchesResource(m::mock('Obj')));
        $this->assertTrue($this->rule->matchesResource('Mockery\\Mock'));
        $this->assertFalse($this->rule->matchesResource('Duck'));
    }

    public function testCanDetermineRelevance()
    {
        $this->assertTrue($this->rule->relevant('read', 'Mockery\\Mock'));
        $this->assertFalse($this->rule->relevant('write', 'Mockery\\Mock'));
    }


    public function testThis()
    {
        $this->assertTrue(true);
    }
}
