<?php

namespace taskforce\logic\actions;

abstract class AbstractAction

{
    /**
     * Возвращает имя действия
     * @return string - наименование действия на кириллице
     */
    abstract public static function getName(): string;

    /**
     * Возвращает код действия
     * @return string - код действия
     */
    abstract public static function getInnerName(): string;

    /**
     * Проверяет права пользователя на возможность выполнить действие
     * @param int $userId - статус задачи
     * @param int $clientId - идентификатор заказчика  
     * @param int $performerId - идентификатор исполнителя 
     * @return bool - true - если идентификатор текущего пользователя совпадает с идентификатором роли, иначе false
     */
    abstract public static function checkRights(int $userId, int $performerId, int $clientId): bool;
}
