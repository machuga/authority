<?php

use Mockery as m;
use Authority\Authority;

class AuthorityTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->user = new stdClass;
        $this->user->id = 1;
        $this->user->name = 'TestUser';

        $this->auth = new Authority($this->user);
    }

    public function tearDown()
    {
        m::close();
    }

    public function testCanStoreCurrentUser()
    {
        $this->assertSame($this->user, $this->auth->getCurrentUser());

        $user = new stdClass;
        $this->auth->setCurrentUser($user);
        $this->assertSame($user, $this->auth->getCurrentUser());
    }

    public function testCanStoreNewPrivilege()
    {
        $rule = $this->auth->allow('read', 'User');
        $this->assertCount(1, $this->auth->getRules());
        $this->assertContains($rule, $this->auth->getRules());
        $this->assertTrue($rule->getBehavior());
    }

    public function testCanStoreNewRestriction()
    {
        $rule = $this->auth->deny('read', 'User');
        $this->assertCount(1, $this->auth->getRules());
        $this->assertContains($rule, $this->auth->getRules());
        $this->assertFalse($rule->getBehavior());
    }

    public function testCanStoreNewAlias()
    {
        $alias = $this->auth->addAlias('manage', array('create', 'read', 'update', 'delete'));
        $this->assertContains($alias, $this->auth->getAliases());
        $this->assertSame($alias, $this->auth->getAlias('manage'));
    }

    public function testCanFetchAliasedActions()
    {
        $this->auth->addAlias('manage', array('create', 'read', 'update', 'delete'));
        $this->auth->addAlias('comment', array('read', 'comment'));

        $this->assertCount(3, $this->auth->getAliasesForAction('read'));
    }

    public function testCanFetchAllRulesForAction()
    {
        $this->auth->addAlias('manage', array('create', 'read', 'update', 'delete'));
        $this->auth->addAlias('comment', array('read', 'comment'));

        $this->auth->allow('manage', 'User');
        $this->auth->allow('comment', 'User');
        $this->auth->deny('read', 'User');

        $this->assertCount(3, $this->auth->getRulesFor('read', 'User'));
    }

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
}
