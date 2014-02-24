<?php

use Mockery as M;
use Authority\Authority;

class AuthorityTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->user = M::mock('User');
        $this->user->id = 1;
        $this->user->name = 'TestUser';

        $this->auth = new Authority($this->user);
        $this->auth->addAlias('manage', ['create', 'read', 'update', 'delete']);
    }

    public function tearDown()
    {
        M::close();
    }

    public function testCanResolveResourcePairFromPair()
    {
        $mock = M::mock('DummyClass');
        $pair = $this->auth->resolveResourcePair('DummyClass', $mock);

        $this->assertEquals(['DummyClass', $mock], $pair);
    }

    public function testCanResolveResourcePairFromName()
    {
        $pair = $this->auth->resolveResourcePair('DummyClass');

        $this->assertEquals(['DummyClass', null], $pair);
    }

    public function testCanResolveResourcePairFromObject()
    {
        $mock = new stdClass;
        $pair = $this->auth->resolveResourcePair($mock);

        $this->assertEquals(['stdClass', $mock], $pair);
    }

    public function testCanStoreNewPrivilege()
    {
        $rule = $this->auth->allow('read', 'User');

        $this->assertContains($rule, $this->auth->getRules());
    }

    public function testCanStoreNewRestriction()
    {
        $rule = $this->auth->deny('read', 'User');

        $this->assertContains($rule, $this->auth->getRules());
    }

    public function testCanStoreNewAlias()
    {
        $alias = $this->auth->addAlias('manage', array('create', 'read', 'update', 'delete'));

        $this->assertContains($alias, $this->auth->getAliases());
    }

    public function testCanFetchAliasedActions()
    {
        $this->auth->addAlias('comment', ['read', 'comment']);

        $this->assertCount(3, $this->auth->namesForAction('read'));
    }

    public function testCanFetchAllRulesForAction()
    {
        $this->auth->addAlias('comment', ['read', 'comment']);

        $this->auth->allow('manage', 'User');
        $this->auth->allow('comment', 'User');
        $this->auth->deny('read', 'User');

        $this->assertCount(3, $this->auth->rulesFor('read', 'User'));
    }

    public function testCanAllowAPrivilege()
    {
        $this->auth->allow('read', 'User');

        $this->assertTrue($this->auth->can('read', 'User'));
    }

    public function testCanAllowAnActionInAnAlias()
    {
        $this->auth->allow('manage', 'User');

        $this->assertTrue($this->auth->can('read', 'User'));
    }

    public function testCanOverridePreviousRule()
    {
        $this->auth->allow('read', 'User');
        $this->auth->deny('read', 'User');

        $this->assertFalse($this->auth->can('read', 'User'));
    }

    public function testCanOverrideActionInAlias()
    {
        $this->auth->allow('manage', 'User');
        $this->auth->deny('read', 'User');

        $this->assertFalse($this->auth->can('read', 'User'));
    }

    public function testAliasedActionBehaviorDoesntDependOnActions()
    {
        $this->auth->allow('manage', 'User');
        $this->auth->deny('read', 'User');

        $this->assertTrue($this->auth->can('manage', 'User'));
    }

    public function testCanAllowPrivilegeWithPassingConstraint()
    {
        $this->auth->allow('read', 'User', function($user) { return true; });

        $this->assertTrue($this->auth->can('read', 'User'));
    }


    public function testCanDenyPrivilegeWithFailingConstraint()
    {
        $this->auth->allow('read', 'User', function($user) { return false; });

        $this->assertFalse($this->auth->can('read', 'User'));
    }

    public function testCanDenyRestrictionWithPassingConstraint()
    {
        $this->auth->deny('read', 'User', function($user) { return true; });

        $this->assertFalse($this->auth->can('read', 'User'));
    }

    public function testCanAllowRestrictionWithFailingConstraint()
    {
        $this->auth->deny('read', 'User', function($user) { return false; });

        $this->assertTrue($this->auth->can('read', 'User'));
    }
/*
    public function testCanEvaluateRulesForAction()
    {
        $this->auth->addAlias('manage', array('create', 'read', 'update', 'delete'));
        $this->auth->addAlias('comment', array('read', 'create'));

        $this->auth->allow('manage', 'User');
        $this->auth->allow('comment', 'User');
        $this->auth->deny('read', 'User');

        $this->assertTrue($this->auth->can('manage', 'User'));
        $this->assertTrue($this->auth->can('create', 'User'));
        $this->assertFalse($this->auth->can('read', 'User'));
        $this->assertFalse($this->auth->can('explodeEverything', 'User'));
        $this->assertTrue($this->auth->cannot('explodeEverything', 'User'));
    }

    public function testCanEvaluateRulesOnObject()
    {
        $user = $this->user;
        $user2 = new stdClass;
        $user2->id = 2;

        $this->auth->allow('comment', 'User', function ($self, $a_user) {
            return $self->getCurrentUser()->id == $a_user->id;
        });

        $this->auth->deny('read', 'User', function ($self, $a_user) {
            return $self->getCurrentUser()->id != $a_user->id;
        });

        $this->assertFalse($this->auth->can('comment', $user));
        $this->assertTrue($this->auth->can('comment', 'User', $user));
        $this->assertFalse($this->auth->can('comment', $user2));
        $this->assertFalse($this->auth->can('comment', 'User', $user2));
    }

    public function testLastRuleOverridesPreviousRules()
    {
        $user = $this->user;

        $this->auth->allow('comment', 'User', function ($self, $a_user) {
            return $self->getCurrentUser()->id != $a_user->id;
        });

        $this->auth->allow('comment', 'User');

        $this->assertFalse($this->auth->can('comment', $user));
    }
*/
}
