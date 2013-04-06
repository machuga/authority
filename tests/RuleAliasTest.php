<?php

use Mockery as m;
use Authority\Rule;
use Authority\RuleAlias;

class RuleAliasTest extends PHPUnit_Framework_TestCase
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
        //$alias->actions();
        //$alias->includes('read');
        $this->assertTrue($alias->includes('read'));
    }
}
