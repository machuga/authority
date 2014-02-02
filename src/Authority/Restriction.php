<?php
namespace Authority;

class Restriction extends Rule
{
    public function isAllowed()
    {
        return ! $this->checkCondition(func_get_args());
    }
}
