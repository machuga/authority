<?php
/**
 * Authority: A simple and flexible authorization system for PHP.
 *
 * @package Authority
 */
namespace Authority;

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Closure;

/**
 * RuleRepository collections contain and interact with Rule instances
 *
 * @package Authority
 */
class RuleRepository implements Countable, ArrayAccess, IteratorAggregate
{
    /**
     * @var array Internal container for the rules
     */
    protected $rules;

    /**
     * RuleRepository constructor
     *
     * @param array $rules Initial list of rules for the collection
     */
    public function __construct(array $rules = array())
    {
        $this->rules = $rules;
    }

    /**
     * Add a rule to the collection
     *
     * @return void
     */
    public function add(Rule $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * Runs a reduce callback on the collection
     *
     * @param Closure   $callback Callback to use for the reduce algorithm
     * @param mixed     $initialValue Initial value for the reduce set
     * @return RuleRepository
     */
    public function reduce(Closure $callback, $initialValue = array())
    {
        $rules = array_reduce($this->rules, $callback, $initialValue);
        return new static($rules);
    }

    /**
     * Get all rules only relevant to the given action and resource
     *
     * @param string $action Action to check against
     * @param string $resource Resource to check against
     * @return RuleRepository
     */
    public function getRelevantRules($action, $resource)
    {
        $rules = array_reduce($this->rules, function($rules, $currentRule) use ($action, $resource) {
            if ($currentRule->isRelevant($action, $resource)) {
                $rules[] = $currentRule;
            }
            return $rules;
        }, array());

        return new static($rules);
    }

    /**
     * Return the first element in the array or null if empty
     *
     * @return Rule|null
     */
    public function first()
    {
        return count($this->rules) > 0 ? reset($this->rules) : null;
    }

    /**
     * Return the last element in the array or null if empty
     *
     * @return Rule|null
     */
    public function last()
    {
        return count($this->rules) > 0 ? end($this->rules) : null;
    }

    /**
     * Return a raw array of all rules
     *
     * @return array
     */
    public function all()
    {
        return $this->rules;
    }

    /**
     * Determine if empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->rules);
    }

    /**
     * Returns an iterator for the internal array
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->rules);
    }

    /**
     * Returns the number of rules
     *
     * @return int
     */
    public function count()
    {
        return count($this->rules);
    }

    /**
     * Determine if the rule exists by key
     *
     * @return boolean
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->rules);
    }

    /**
     * Returns the requested Rule
     *
     * @return Rule
     */
    public function offsetGet($key)
    {
        return $this->rules[$key];
    }

    /**
     * Sets the rule at the given key
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->rules[$key] = $value;
    }

    /**
     * Unsets the rule at the given key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->rules[$key]);
    }
}
