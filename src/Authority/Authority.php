<?php
/**
 * Authority: A simple and flexible authorization system for PHP.
 *
 * @package Authority
 */


/**
 * Plan:
   Authority contains an Evaluator
   Evaluator takes rules and aliases (or maybe just lists of actions/rules?)
   Evaluator->evaluate accepts a challenge
 */
namespace Authority;

class Authority
{
    protected $user;
    protected $rules;
    protected $aliases = [];

    public function __construct($currentUser, $listener = null)
    {
        $this->user     = $currentUser;
        $this->rules    = new RuleRepository;
        $this->listener = $listener ?: new NullListener();
    }

    public function can($action, $resource, $resourceValue = null)
    {
        $challenge = new Challenge($action, $resource, $resourceValue);

        $rules = $this->rulesFor($challenge->getAction(), $challenge->getResource());

        $evaluator = new Evaluator($rules, $this);
        return $evaluator->check($challenge);
    }

    public function cannot($action, $resource, $resourceValue = null)
    {
        return ! $this->can($action, $resource, $resourceValue);
    }

    public function allow($action, $resource, $condition = null)
    {
        return $this->addRule(new Privilege($action, $resource, $condition));
    }

    public function deny($action, $resource, $condition = null)
    {
        return $this->addRule(new Restriction($action, $resource, $condition));
    }

    protected function addRule(Rule $rule)
    {
        $this->rules->add($rule);
        return $rule;
    }

    public function addAlias($name, $actions)
    {
        $alias = new RuleAlias($name, $actions);
        $this->aliases[$name] = $alias;
        return $alias;
    }

    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
    }

    public function rulesFor($action, $resource)
    {
        $aliases = $this->namesForAction($action);
        return $this->rules->getRelevantRules($aliases, $resource);
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function namesForAction($action)
    {
        $actions = array($action);

        foreach ($this->aliases as $key => $alias) {
            if ($alias->includes($action)) {
                $actions[] = $key;
            }
        }

        return $actions;
    }

    public function resolveResourcePair($resource, $resourceValue = null)
    {
        if (! is_string($resource)) {
            $resourceValue = $resource;
            $resource      = get_class($resourceValue);
        }

        return [$resource, $resourceValue];
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    public function getAlias($name)
    {
        return $this->aliases[$name];
    }

    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    public function user()
    {
        return $this->getCurrentUser();
    }
}
