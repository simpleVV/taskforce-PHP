<?php

namespace taskforce\logic\actions;

abstract class AbstractAction

{
    /**
     * Returns the name of the action
     * 
     * @return string - the name of the action in Cyrillic
     */
    abstract public static function getName(): string;

    /**
     * Returns the action code
     * 
     * @return string - action code
     */
    abstract public static function getInnerName(): string;

    /**
     * Checks the user's rights to be able to perform the action
     * 
     * @param int $userId - task status
     * @param int $clientId - client ID  
     * @param int $performerId - performer ID 
     * @return bool - true - if the ID of the current user matches the ID of the role, otherwise false
     */
    abstract public static function checkRights(int $userId, int $performerId, int $clientId): bool;
}
