<?php
/**
 * Authority: A simple and flexible authorization system for PHP.
 *
 * @package Authority
 */
namespace Authority;

/**
 * Rule instances are value objects with simple introspection.
 *
 * @package Authority
 */
abstract class Rule
{
    /**
     * @var string Action the rule applies to
     */
    protected $action;

    /**
     * @var string Resource the rule applies to
     */
    protected $resource;

    /**
     * @var callable Conditional closure to further evaluate
     */
    protected $condition;

    /**
     * @constant string Wildcard for any matching resource
     */
    const WILDCARD = 'all';

    /**
     * Rule constructor
     *
     * @param string        $action Action the rule applies to
     * @param string        $resource Name of resource
     * @param Closure|null  $condition Optional closure to act as a condition
     */
    public function __construct($action, $resource, $condition = null)
    {
        $this->action    = $action;
        $this->resource  = $resource;
        $this->condition = $condition;
    }

    /**
     * Determine if the current rule is considered to be allowed
     *
     * @return boolean
     */
    abstract public function isAllowed();

    /**
     * Determine if current rule is relevant based on an action and resource
     *
     * @param string        $action Action in question
     * @param string|mixed  $resource Name of resource or instance of object
     * @return boolean
     */
    public function isRelevant($action, $resource)
    {
        return $this->matchesAction($action) && $this->matchesResource($resource);
    }

    /**
     * Determine if the instance's action matches the one passed in
     *
     * @param string $action Action in question
     * @return boolean
     */
    public function matchesAction($action)
    {
        return in_array($this->action, (array) $action);
    }

    /**
     * Determine if the instance's resource matches the one passed in
     *
     * @param string|mixed $resource Name of resource or instance of object
     * @return boolean
     */
    public function matchesResource($resource)
    {
        return in_array($this->resource, [$resource, static::WILDCARD]);
    }

    /**
     * Returns action this rule represents
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Returns resource this rule represents
     *
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Returns condition
     *
     * @return boolean
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Invokes condition if it exists
     *
     * @return mixed|null
     */
    public function checkCondition($argv = [])
    {
        $callback = $this->condition;
        $argc     = count($argv);
        $result   = null;

        if ($callback) {
            if ($argc === 0) {
                $result = $callback();
            } elseif ($argc === 1) {
                $result = $callback($argv[0]);
            } else {
                $result = call_user_func_array($callback, func_get_args());
            }
            return $result;
        }
    }

    public function __invoke()
    {
        return $this->checkCondition(func_get_args());
    }
}
