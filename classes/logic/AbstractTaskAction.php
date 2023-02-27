<?php

namespace taskforce\logic;

abstract class AbstractTaskAction

{
    public array $actions_names;
    public array $inner_names;

    public function __construct(array $actions_names, array $inner_names)
    {
        $this->actions_names = $actions_names;
        $this->inner_names = $inner_names;
    }

    abstract public function getActionName($action_name);

    abstract public function getInnerActionName($inner_name);

    abstract public function verificationRights($currentUserId, ?int $clientId = null, ?int $performerId = null);
}

class RespondAction extends AbstractTaskAction
{
    public function getActionName($action_name)
    {
        return $this->actions_names[$action_name] ?? null;
    }

    public function getInnerActionName($inner_name)
    {
        return $this->inner_names[$inner_name] ?? null;
    }

    public function
    verificationRights($currentUserId, ?int $clientId = null, ?int $performerId = null)
    {
        return $performerId == $currentUserId ? true : false;
    }
}

class CancelAction extends AbstractTaskAction
{
    public function getActionName($action_name)
    {
        return $this->actions_names[$action_name] ?? null;
    }

    public function getInnerActionName($inner_name)
    {
        return $this->inner_names[$inner_name] ?? null;
    }

    public function
    verificationRights($currentUserId, ?int $clientId = null, ?int $performerId = null)
    {
        return $clientId == $currentUserId ? true : false;
    }
}

class RefusalAction extends AbstractTaskAction
{
    public function getActionName($action_name)
    {
        return $this->actions_names[$action_name] ?? null;
    }

    public function getInnerActionName($inner_name)
    {
        return $this->inner_names[$inner_name] ?? null;
    }

    public function
    verificationRights($currentUserId, ?int $clientId = null, ?int $performerId = null)
    {
        return $performerId == $currentUserId ? true : false;
    }
}

class CompleteAction extends AbstractTaskAction
{
    public function getActionName($action_name)
    {
        return $this->actions_names[$action_name] ?? null;
    }

    public function getInnerActionName($inner_name)
    {
        return $this->inner_names[$inner_name] ?? null;
    }

    public function
    verificationRights($currentUserId, ?int $clientId = null, ?int $performerId = null)
    {
        return $clientId == $currentUserId ? true : false;
    }
}
