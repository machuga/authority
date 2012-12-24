<?php

use Mockery as m;
use Authority\Authority;

class AuthorityTest extends PHPUnit_Framework_Testcase
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
}
