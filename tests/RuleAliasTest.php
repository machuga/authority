<?php

use Mockery as m;
use Authority\Rule;
use Authority\RuleAlias;

class RuleAliasTest extends PHPUnit_Framework_Testcase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
        m::close();
    }

    public function testCanAliasAction()
    {
        $alias = new RuleAlias('manage', array('create', 'read', 'update', 'delete'));
        $this->assertTrue($alias->includes('create'));
        $this->assertTrue($alias->includes('read'));
        $this->assertTrue($alias->includes('update'));
        $this->assertTrue($alias->includes('delete'));
    }
}
