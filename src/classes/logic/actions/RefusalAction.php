<?php

namespace taskforce\logic\actions;

class RefusalAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'Отказаться';
    }

    public static function getInnerName(): string
    {
        return 'act_refusal';
    }

    public static function
    checkRights($userId, $performerId, $clientId): bool
    {
        return $userId == $performerId;
    }
}
