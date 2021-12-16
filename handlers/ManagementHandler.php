<?php

include_once CI_CACHE . '/handlers/Handler.php';

class ManagementHandler extends Handler
{
    public function __construct()
    {

    }

    public function runAction()
    {
        $action = $this->getAction();
        $this->{$action}();
    }

    protected function hasAction(): bool
    {
        $action = $this->getAction();
        return !is_null($action) && method_exists($this, $action);
    }

    protected function getAction()
    {
        return isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : null;
    }

    protected function index(){}
}