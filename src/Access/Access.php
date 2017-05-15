<?php
namespace Components\Access\Access;

require_once __DIR__.'/AccessDeniedException.php';

class Access
{
    protected $policies = [];

    protected $denial;

    public function addPolicy(Policy $policy)
    {
        if ($this->hasPolicy($policy->getName())) {
            throw new \Exception(
                "Policy \"{$policy->getName()}\" is already defined."
            );
        }

        $this->policies[$policy->getName()] = $policy;
    }

    public function setDenial($callback)
    {
        $this->denial = $callback;
    }

    protected function hasPolicy($name)
    {
        return isset($this->policies[$name]);
    }

    protected function getPolicy($name)
    {
        return $this->hasPolicy($name) ? $this->policies[$name] : false;
    }

    protected function enforcePolicy($name,$parameters)
    {
        if (!$policy = $this->getPolicy($name)) {
            throw new \Exception("Policy \"$name\" is not defined.");
        }

        return $policy->enforce($parameters);
    }

    protected function deny($policy)
    {
        if (!isset($this->denial)) {
            throw new AccessDeniedException;
        }

        $denial = $this->denial;

        return $denial($policy);
    }

    public function insist($policy,$parameters=[])
    {
        if (!$this->enforcePolicy($policy,$parameters)) {
            $this->deny($policy);
        }

        return true;
    }

    public function check($policy,$parameters=[])
    {
        return $this->enforcePolicy($policy,$parameters);
    }
}
