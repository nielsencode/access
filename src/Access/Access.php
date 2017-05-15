<?php
namespace Components\Access;

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
        if (!$this->hasPolicy($name)) {
            throw new \Exception("Policy \"$name\" is not defined.");
        }

        return $this->policies[$name];
    }

    protected function deny(Policy $policy,$parameters)
    {
        if (method_exists($policy,'violation')) {
            return $policy->violate($parameters);
        }

        if (isset($this->denial)) {
            $denial = $this->denial;

            return $denial($policy,$parameters);
        }

        throw new AccessDeniedException;
    }

    public function insist($policy,$parameters=[])
    {
        $policy = $this->getPolicy($policy);

        if (!$policy->enforce($parameters)) {
            $this->deny($policy,$parameters);
        }

        return true;
    }

    public function check($policy,$parameters=[])
    {
        $policy = $this->getPolicy($policy);

        return $policy->enforce($parameters);
    }
}
