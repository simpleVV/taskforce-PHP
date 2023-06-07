<?php

namespace taskforce\logic\actions;


class CompleteAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'Выполнено';
    }

    public static function getInnerName(): string
    {
        return 'act_complete';
    }

    public static function
    checkRights($userId, $performerId, $clientId): bool
    {
        return $userId == $clientId;
    }
}
