<?php

namespace taskforce\logic\actions;

use taskforce\logic\actions;

class RespondAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'Откликнуться';
    }

    public static function getInnerName(): string
    {
        return 'act_respond';
    }

    public static function
    checkRights($userId, $performerId, $clientId): bool
    {
        return $userId == $performerId;
    }
}
