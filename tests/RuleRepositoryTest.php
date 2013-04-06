<?php

use Mockery as m;
use Authority\Rule;
use Authority\RuleRepository;

class RuleRepositoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->repo = new RuleRepository;

        $this->rules = array(
            new Rule(true, 'read', 'Obj'),
            new Rule(false, 'write', 'Obj')
        );
    }

    public function tearDown()
    {
        m::close();
    }

    public function testCanStoreRules()
    {
        $repo = new RuleRepository($this->rules);
        $repo->add(new Rule(true, 'delete', 'Obj'));
        $this->assertCount(3, $repo);
    }

    public function testCanReturnEndRules()
    {
        $repo = new RuleRepository($this->rules);
        $this->assertSame($this->rules[0], $repo->first());
        $this->assertSame($this->rules[1], $repo->last());
    }

    public function testCanNarrowRulesByReduce()
    {
        $repo = new RuleRepository($this->rules);
        $rules = $repo->reduce(function($rules, $currentRule) {
            if ($currentRule->isPrivilege()) {
                $rules[] = $currentRule;
            }
            return $rules;
        });
        $this->assertCount(1, $rules);
    }

    public function testCanFetchRelevantRules()
    {
        $repo = new RuleRepository($this->rules);
        $this->assertCount(1, $repo->getRelevantRules('read', 'Obj'));

        $repo->add(new Rule(true, 'read', 'Obj'));
        $this->assertCount(2, $repo->getRelevantRules('read', 'Obj'));
    }
}
