<?php
namespace Authority;

class RuleAlias
{
    protected $alias;
    protected $actions;

    public function __construct($alias, $actions)
    {
        $this->alias = $alias;
        $this->actions = $actions;
    }

    public function includes($action)
    {
        return in_array($action, $this->actions);
    }
}
