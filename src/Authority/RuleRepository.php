<?php
namespace Authority;

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Closure;

class RuleRepository implements Countable, ArrayAccess, IteratorAggregate
{
    protected $rules;

    public function __construct(array $rules = array())
    {
        $this->rules = $rules;
    }

    public function add(Rule $rule)
    {
        $this->rules[] = $rule;
    }

    public function reduce(Closure $callback, $initialValue = array(), $asArray = false)
    {
        $rules = array_reduce($this->rules, $callback, $initialValue);
        if ($rules && ! $asArray) {
            $rules = new static($rules);
        }
        return $rules;
    }

    public function getRelevantRules($action, $resource, $asArray = false)
    {
        $rules = array_reduce($this->rules, function($rules, $currentRule) use ($action, $resource) {
            if ($currentRule->relevant($action, $resource)) {
                $rules[] = $currentRule;
            }
            return $rules;
        });

        if ($rules && ! $asArray) {
            $rules = new static($rules);
        }
        return $rules;
    }

    public function first()
    {
        return count($this->rules) > 0 ? reset($this->rules) : null;
    }

    public function last()
    {
        return count($this->rules) > 0 ? end($this->rules) : null;
    }

    public function all()
    {
        return $this->rules;
    }

    public function isEmpty()
    {
        return empty($this->rules);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->rules);
    }

    public function count()
    {
        return count($this->rules);
    }

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->rules);
    }

    public function offsetGet($key)
    {
        return $this->rules[$key];
    }

    public function offsetSet($key, $value)
    {
        $this->rules[$key] = $value;
    }

    public function offsetUnset($key)
    {
        unset($this->rules[$key]);
    }
}
