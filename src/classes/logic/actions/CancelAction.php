<?php

namespace taskforce\logic\actions;

class CancelAction extends AbstractAction
{

    public static function getName(): string
    {
        return 'Отменить задание';
    }

    public static function getInnerName(): string
    {
        return 'cancel';
    }

    public static function
    checkRights($userId, $performerId, $clientId): bool
    {
        return $userId == $clientId;
    }
}
