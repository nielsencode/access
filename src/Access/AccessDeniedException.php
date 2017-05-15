<?php
namespace Components\Access;

class AccessDeniedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Access denied.');
    }
}
