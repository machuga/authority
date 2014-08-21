<?php
namespace Authority;

abstract class Rule
{
    protected $action;
    protected $resource;
    protected $condition;

    const WILDCARD = 'all';

    public function __construct($action, $resource, $condition = null)
    {
        $this->action    = $action;
        $this->resource  = $resource;
        $this->condition = $condition;
    }

    abstract public function isAllowed();

    public function isRelevant($action, $resource)
    {
        return $this->matchesAction($action) && $this->matchesResource($resource);
    }

    public function matchesAction($action)
    {
        return in_array($this->action, (array) $action);
    }

    public function matchesResource($resource)
    {
        return in_array($this->resource, [$resource, static::WILDCARD]);
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function checkCondition($argv = [])
    {
        $callback = $this->condition;
        $argc     = count($argv);
        $result   = true;

        if ($callback) {
            if ($argc === 0) {
                $result = $callback();
            } elseif ($argc === 1) {
                $result = $callback($argv[0]);
            } else {
                $result = call_user_func_array($callback, func_get_args());
            }
        }
        return $result;
    }
}
