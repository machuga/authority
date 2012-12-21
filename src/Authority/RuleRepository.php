<?php
namespace Authority;

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

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
