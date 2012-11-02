<?php
namespace Authority;

class Rule {

    protected $action;
    protected $behavior;

    public function __construct($behavior, $action, $resource)
    {
        $this->behavior = $behavior;
        $this->action = $action;
        $this->setResource($resource);
    }

    public function matchesAction($action)
    {
        return $this->action === $action;
    }

    public function matchesResource($resource)
    {
        $resource = is_object($resource) ? get_class($resource) : $resource;
        return $this->resource === $resource;
    }

    public function relevant($action, $resource)
    {
        return $this->matchesAction($action) && $this->matchesResource($resource);
    }

    public function setResource($resource)
    {
        $this->resource = is_object($resource) ? get_class($resource) : $resource;
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
