<?php
namespace Authority;

class Authority
{
    public $currentUser;
    public $rules;
    public $aliases = array();

    public function __construct($currentUser)
    {
        $this->rules = new RuleRepository;
        $this->setCurrentUser($currentUser);
    }

    public function can($action, $resource, $resourceValue = null)
    {
        if (! is_string($resource)) {
            $resourceValue = $resource;
            $resource = get_class($resourceValue);
        }

        $allowed = false;

        $rules = $this->getRulesFor($action, $resource);

        if (! $rules->isEmpty()) {
            $allowed = array_reduce($rules->all(), function($result, $rule) use ($resourceValue) {
                $result = $result && $rule->isAllowed($resourceValue);
                return $result;
            }, true);
        }

        return $allowed;
    }

    public function cannot($action, $resource, $resourceValue = null)
    {
        return ! $this->can($action, $resource, $resourceValue);
    }

    public function allow($action, $resource, $resourceValue = null)
    {
        return $this->addRule(true, $action, $resource, $resourceValue);
    }

    public function deny($action, $resource, $resourceValue = null)
    {
        return $this->addRule(false, $action, $resource, $resourceValue);
    }

    public function addRule($allow, $action, $resource, $resourceValue)
    {
        $rule = new Rule($allow, $action, $resource, $resourceValue);
        $this->rules->add($rule);
        return $rule;
    }

    public function addAlias($name, $actions)
    {
        $alias = new RuleAlias($name, $actions);
        $this->aliases[$name] = $alias;
        return $alias;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function getAliasesForAction($action)
    {
        $actions = array($action);

        foreach ($this->aliases as $key => $alias) {
            if ($alias->includes($action)) {
                $actions[] = $key;
            }
        }

        return $actions;
    }

    public function getRulesFor($action, $resource)
    {
        $aliases = $this->getAliasesForAction($action);
        return $this->rules->reduce(function($rules, $currentRule) use ($aliases) {
            if (in_array($currentRule->getAction(), $aliases)) {
                $rules[] = $currentRule;
            }
            return $rules;
        });
    }

    public function getAlias($name)
    {
        return $this->aliases[$name];
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
    }
}
