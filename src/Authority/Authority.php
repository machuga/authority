<?php
/**
 * Authority: A simple and flexible authorization system for PHP.
 *
 * @package Authority
 */
namespace Authority;

class NullListener {}
/**
 * Authority allows for establishing rules to check against for authorization
 *
 * @package Authority
 */
class Authority
{
    /**
     * @var mixed Current user in the application for rules to apply to
     */
    protected $currentUser;

    /**
     * @var RuleRepository Collection of rules
     */
    protected $rules;

    /**
     * @var array List of aliases for groups of actions
     */
    protected $aliases = [];

    /**
     * Authority constructor
     *
     * @param mixed $currentUser Current user in the application
     * @param mixed $listener    Listener for events on the authority
     */
    public function __construct($currentUser, $listener = null)
    {
        $this->user     = $currentUser;
        $this->rules    = new RuleRepository;
        $this->listener = $listener ?: new NullListener();
    }

    /**
     * Determine if current user can access the given action and resource
     *
     * @return boolean
     */
    public function can($action, $resource, $resourceValue = null)
    {
        list($resource, $resourceValue) =
            $this->resolveResourcePair($resource, $resourceValue);

        $rules = $this->rulesFor($action, $resource);

        if (! $rules->isEmpty()) {
            $allowed = $rules->reduce(function($result, $rule) use ($resourceValue) {
                $condition = $rule->getCondition();
                $condition && $condition->bindTo($this);
                return $result && $rule->isAllowed($resourceValue);
            }, true);

            $last = $rules->last();

            $condition = $last->getCondition();
            $condition && $condition->bindTo($this);
            $allowed = $allowed || $last->isAllowed($resourceValue);
        } else {
            $allowed = false;
        }
        return $allowed;
    }

    /**
     * Determine if current user cannot access the given action and resource
     * Returns negation of can()
     *
     * @return boolean
     */
    public function cannot($action, $resource, $resourceValue = null)
    {
        return ! $this->can($action, $resource, $resourceValue);
    }

    /**
     * Define privilege for a given action and resource
     *
     * @param string        $action Action for the rule
     * @param mixed         $resource Resource for the rule
     * @param Closure|null  $condition Optional condition for the rule
     * @return Rule
     */
    public function allow($action, $resource, $condition = null)
    {
        return $this->addRule(new Privilege($action, $resource, $condition));
    }

    /**
     * Define restriction for a given action and resource
     *
     * @param string        $action Action for the rule
     * @param mixed         $resource Resource for the rule
     * @param Closure|null  $condition Optional condition for the rule
     * @return Rule
     */
    public function deny($action, $resource, $condition = null)
    {
        return $this->addRule(new Restriction($action, $resource, $condition));
    }

    /**
     * Add rule to collection
     *
     * @param Privilege|Restriction Rule to be added to the collection
     * @return Privilege|Restriction
     */
    protected function addRule(Rule $rule)
    {
        $this->rules->add($rule);
        return $rule;
    }

    /**
     * Define new alias for an action
     *
     * @param string $name Name of action
     * @param array  $actions Actions that $name aliases
     * @return RuleAlias
     */
    public function addAlias($name, $actions)
    {
        $alias = new RuleAlias($name, $actions);
        $this->aliases[$name] = $alias;
        return $alias;
    }

    /**
     * Set current user
     *
     * @param mixed $currentUser Current user in the application
     * @return void
     */
    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * Returns all rules relevant to the given action and resource
     *
     * @return RuleRepository
     */
    public function rulesFor($action, $resource)
    {
        $aliases = $this->namesForAction($action);
        return $this->rules->getRelevantRules($aliases, $resource);
    }

    /**
     * Returns the current rule set
     *
     * @return RuleRepository
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Returns all actions a given action applies to
     *
     * @return array
     */
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

    /**
     * Cleanly resolve a resource name and pair
     *
     * @param mixed $resource      Resource value or name
     * @param mixed $resourceValue Resource value
     */
    public function resolveResourcePair($resource, $resourceValue = null)
    {
        if (! is_string($resource)) {
            $resourceValue = $resource;
            $resource      = get_class($resourceValue);
        }

        return [$resource, $resourceValue];
    }

    /**
     * Returns all aliases
     *
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Returns a RuleAlias for a given action name
     *
     * @return RuleAlias|null
     */
    public function getAlias($name)
    {
        return $this->aliases[$name];
    }

    /**
     * Returns current user
     *
     * @return mixed
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Returns current user - alias of getCurrentUser()
     *
     * @return mixed
     */
    public function user()
    {
        return $this->getCurrentUser();
    }
}
