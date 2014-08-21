<?php
namespace Authority;

#allow('manage', 'User')
#deny('delete', 'User')
class Evaluator
{
    public function __construct($rules)
    {
        $this->rules = $rules;
        $this->challenge = null;
    }

    public function check($challenge)
    {
        $this->challenge = $challenge;

        if (! $this->rules->isEmpty()) {
            $allowed = $this->rules();

            $last = $this->rules->last();

            $condition = $last->getCondition();
            $condition && $condition->bindTo($this);
            $allowed = $allowed || $last->isAllowed($resourceValue);
        } else {
            $allowed = false;
        }
    }

    protected function rules()
    {
        return $this->rules->reduce(function($result, $rule) {
            $condition = $rule->getCondition();
            $condition && $condition->bindTo($this);
            return $result && $rule->isAllowed($this->challenge->getResourceValue());
        }, true);
    }
}
