<?php

use Mockery as M;
use Authority\RuleRepository;

class RuleRepositoryTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->repo = new RuleRepository;

        $this->relevantRule   = M::mock('Authority\Rule');
        $this->irrelevantRule = M::mock('Authority\Rule');

        $this->relevantRule->shouldReceive('isRelevant')->andReturn(true);
        $this->irrelevantRule->shouldReceive('isRelevant')->andReturn(false);

        $this->rules = [$this->relevantRule, $this->irrelevantRule];
        $this->repo  = new RuleRepository($this->rules);
    }

    public function tearDown()
    {
        M::close();
    }

    public function testCanStoreRules()
    {
        $repo = new RuleRepository();
        $repo->add($this->relevantRule);
        $this->assertCount(1, $repo);
    }

    public function testCanFilterRules()
    {
        $result = $this->repo->filter(function($item) {
            return $item->isRelevant(null, null);
        });

        $this->assertContainsOnly($this->relevantRule, $result);
    }

    public function testCanReturnOnlyRelevantRules()
    {
        $result = $this->repo->getRelevantRules('read', 'Post');
        $this->assertContainsOnly($this->relevantRule, $result);
    }

}
