<?php

use Mockery as M;
use Authority\RuleRepository;
use Authority\Evaluator;
use Authority\Privilege;
use Authority\Challenge;

class EvaluatorTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $rules = new RuleRepository([
            new Privilege('read', 'User'),
            new Privilege('read', 'Post'),
            new Privilege('edit', 'User', function($user) {
                return $user->getId() === $this->user->getId();
            }),
        ]);
        $this->evaluator = new Evaluator($rules);
    }

    public function tearDown()
    {
        M::close();
    }

    public function testCanAllowAPrivilege()
    {
        $challenge = new Challenge('read', 'User');

        $this->assertTrue($this->evaluator->check($challenge));
    }
}
