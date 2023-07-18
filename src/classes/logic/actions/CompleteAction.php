<?php

namespace taskforce\logic\actions;


class CompleteAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'Завершить задание';
    }

    public static function getInnerName(): string
    {
        return 'completion';
    }

    public static function
    checkRights($userId, $performerId, $clientId): bool
    {
        return $userId == $clientId;
    }
}
