<?php
namespace Authority;

class Rule {

    protected $action;
    protected $behavior;

    public function __construct($behavior, $action, $resource)
    {
        $this->setAction($action);
        $this->setBehavior($behavior);
        $this->setResource($resource);
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

    public function isAllowed()
    {
        return $this->getBehavior();
    }

    public function isDenied()
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
