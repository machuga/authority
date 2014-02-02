<?php
namespace Authority;

class Privilege extends Rule
{
    public function isAllowed()
    {
        return $this->checkCondition(func_get_args());
    }
}
