<?php
namespace Authority;

class Rule {

    protected $action;
    protected $behavior;
    protected $conditions = array();

    public function __construct($behavior, $action, $resource, $condition = null)
    {
        $this->setAction($action);
        $this->setBehavior($behavior);
        $this->setResource($resource);
        $this->addCondition($condition);
    }

    public function matchesAction($action)
    {
        return $this->action === $action;
    }

    public function matchesResource($resource)
    {
        $resource = is_object($resource) ? get_class($resource) : $resource;
        return $this->resource === $resource || $this->resource === 'all';
    }

    public function relevant($action, $resource)
    {
        return $this->matchesAction($action) && $this->matchesResource($resource);
    }

    public function isAllowed($resource)
    {
        return ! $resource || $this->evaluteConditions($resource);
    }

    public function when($condition)
    {
        return $this->addCondition($condition);
    }

    public function evaluteConditions($resource)
    {
        $result = array_reduce($this->conditions, function($results, $condition) use ($resource) {
            return $results && $condition($resource);
        }, true);

        return $result;
    }

    public function isPrivilege()
    {
        return $this->getBehavior();
    }

    public function isRestriction()
    {
        return ! $this->getBehavior();
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function setBehavior($behavior)
    {
        $this->behavior = $behavior;
    }

    public function setResource($resource)
    {
        $this->resource = is_object($resource) ? get_class($resource) : $resource;
    }

    public function addCondition($condition)
    {
        $this->conditions[] = $condition;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getBehavior()
    {
        return $this->behavior;
    }

    public function getResource()
    {
        return $this->resource;
    }
}
