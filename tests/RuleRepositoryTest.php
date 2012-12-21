<?php

use Mockery as m;
use Authority\Rule;
use Authority\RuleRepository;

class RuleRepositoryTest extends PHPUnit_Framework_Testcase
{
    public function setUp()
    {
        $this->repo = new RuleRepository;

        $this->rules = [
            new Rule(true, 'read', m::mock('Obj')),
            new Rule(false, 'write', m::mock('Obj'))
        ];
    }

    public function tearDown()
    {
        m::close();
    }

    public function testCanStoreRules()
    {
        $repo = new RuleRepository($this->rules);
        $repo->add(new Rule(true, 'delete', m::mock('Obj')));
        $this->assertCount(3, $repo);
    }

    public function testCanReturnEndRules()
    {
        $repo = new RuleRepository($this->rules);
        $this->assertSame($this->rules[0], $repo->first());
        $this->assertSame($this->rules[1], $repo->last());
    }
}
