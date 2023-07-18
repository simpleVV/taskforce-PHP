<?php

namespace taskforce\logic\actions;

class RefusalAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'Отказаться от задания';
    }

    public static function getInnerName(): string
    {
        return 'refusal';
    }

    public static function
    checkRights($userId, $performerId, $clientId): bool
    {
        return $userId == $performerId;
    }
}
