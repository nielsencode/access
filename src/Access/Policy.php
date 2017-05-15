<?php
namespace Access\Access;

abstract class Policy
{
    protected $name;

    public function getName()
    {
        return $this->name;
    }

    public function enforce($parameters)
    {
        return call_user_func_array([$this,'policy'],$parameters);
    }
}
