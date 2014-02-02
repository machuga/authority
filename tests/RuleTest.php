<?php

use Mockery as M;

class Rule extends Authority\Rule
{
    public function isAllowed() { return true; }
}

class RuleTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->action          = 'read';
        $this->resourceClass   = 'Obj';
        $this->resource        = M::mock($this->resourceClass);
        $this->condition       = function($obj) { return $obj->getId() === 1; };
        $this->rule            = new Rule('read', $this->resourceClass);
        $this->conditionalRule = new Rule('read', $this->resourceClass, $this->condition);

        $this->resource->shouldReceive('getId')->andReturn(1);
    }

    public function tearDown()
    {
        M::close();
    }

    public function testCanMatchValidAction()
    {
        $this->assertTrue($this->rule->matchesAction($this->action));
    }

    public function testCanNotMatchDifferentAction()
    {
        $this->assertFalse($this->rule->matchesAction('someDifferentAction'));
    }

    public function testCanMatchValidResource()
    {
        $this->assertTrue($this->rule->matchesResource($this->resourceClass));
    }

    public function testCanNotMatchDifferentResource()
    {
        $this->assertFalse($this->rule->matchesResource('someDifferentResource'));
    }

    public function testWildcardCanMatchResource()
    {
        $rule = new Rule('read', 'all');

        $this->assertTrue($rule->matchesResource('all'));
    }

    public function testWildcardResourceCanMatchAnyResource()
    {
        $rule = new Rule('read', 'all');

        $this->assertTrue($rule->matchesResource($this->resourceClass));
    }

    public function testCanDetermineRelevanceWhenMatching()
    {
        $this->assertTrue($this->rule->isRelevant($this->action, $this->resourceClass));
    }

    public function testCanDetermineIrrelevanceWhenActionNotMatching()
    {
        $this->assertFalse($this->rule->isRelevant('someDifferentAction', $this->resourceClass));
    }

    public function testCanDetermineIrrelevanceWhenResourceNotMatching()
    {
        $this->assertFalse($this->rule->isRelevant($this->action, 'someDifferentResource'));
    }

    public function testCanDetermineIrrelevanceWhenBothNotMatching()
    {
        $this->assertFalse($this->rule->isRelevant('someDifferentAction', 'someDifferentResource'));
    }

    public function testInvokingWithoutConditionWillBeNull()
    {
        $rule = $this->rule;
        $this->assertNull($rule());
    }

    public function testInvokingWithConditionWillReturnResult()
    {
        $rule = $this->conditionalRule;
        $this->assertTrue($rule($this->resource));
    }
}
