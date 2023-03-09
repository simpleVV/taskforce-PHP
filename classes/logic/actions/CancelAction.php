<?php

namespace taskforce\logic\actions;

class CancelAction extends AbstractAction
{

    public static function getName(): string
    {
        return "Отменить";
    }

    public static function getInnerName(): string
    {
        return "act_cancel";
    }

    public static function
    checkRights($userId, $performerId, $clientId): bool
    {
        return $userId == $clientId;
    }
}
