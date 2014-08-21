<?php
/**
 * Authority: A simple and flexible authorization system for PHP.
 *
 * @package Authority
 */
namespace Authority;

class Challenge
{
    protected $action;
    protected $resource;
    protected $resourceValue;

    public function __construct($action, $resource, $resourceValue = null)
    {
        $this->action = $action;

        if (is_string($resource)) {
            $this->resource      = $resource;
            $this->resourceValue = $resourceValue;
        } else {
            $this->resource      = get_class($resource);
            $this->resourceValue = $resource;
        }
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getResourceValue()
    {
        return $this->resourceValue;
    }

    public function getResourcePair()
    {
        return [$this->getResource(), $this->getResourceValue()];
    }
}
