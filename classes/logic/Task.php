<?php

namespace taskforce\logic;

use DateTime;
use taskforce\logic\actions\CancelAction;
use taskforce\logic\actions\CompleteAction;
use taskforce\logic\actions\RefusalAction;
use taskforce\logic\actions\RespondAction;
use taskforce\utils\exception\ValidRoleException;
use taskforce\utils\exception\ValidStatusException;
use taskforce\utils\exception\ValidDateExeption;

class Task
{
    const STATUS_NEW = "new";
    const STATUS_CANCEL = "cancel";
    const STATUS_IN_PROGRESS = "in_progress";
    const STATUS_COMPLETE = "complete";
    const STATUS_FAILED = "fail";

    const ROLE_PERFORMER = "performer";
    const ROLE_CLIENT = "client";

    private ?int $performerId;
    private int $clientId;
    private string $status;
    private DateTime $finishDate;

    /**
     * @param string $status - статус задачи
     * @param int $clientId - идентификатор заказчика  
     * @param int|null $performerId - идентификатор исполнителя 
     * @return void
     */
    public function __construct(string $status, int $clientId, ?int $performerId)
    {
        $this->performerId = $performerId;
        $this->clientId = $clientId;

        $this->setStatus($status);
    }

    /**
     * Устанавливает дату окончания задачи
     * @param DateTime $date - дата окончания задачи
     * @return void
     */
    public function setFinishDate(DateTime $date): void
    {
        $curDate = new DateTime();

        $date > $curDate
            ? $this->finishDate = $date
            : throw new ValidDateExeption('Дата выполнения задания не может быть меньше текущей даты');
    }

    /**
     * Возвращает массив статусов задачи, где ключ — внутреннее имя, а значение 
     * названия статуса на русском
     * @return array - массив статусов
     */
    public function getStatusesMap(): array
    {
        return [
            self::STATUS_NEW => "Новое",
            self::STATUS_CANCEL => "Отменено",
            self::STATUS_IN_PROGRESS => "В работе",
            self::STATUS_COMPLETE => "Выполнено",
            self::STATUS_FAILED => "Провалено"
        ];
    }

    /**
     * Возвращает следующий статус задачи после выполненного действия  
     * @param string $action - выполненное действие 
     * @return ?string - статус задания или null если нет статуса для выполненного действия
     */
    public function getNextStatus(string $action): ?string
    {
        $map = [
            CompleteAction::class => self::STATUS_COMPLETE,
            CancelAction::class => self::STATUS_CANCEL,
            RefusalAction::class => self::STATUS_CANCEL,
        ];

        return $map[$action] ?? null;
    }

    /**
     * Возвращает доступные действия для указанного статуса и роли
     * @param int $userId - id текущего пользователя
     * @param string $userRole - роль пользователя
     * @return array - массив доступных действия
     */
    public function getAvailableActions(int $userId, string $userRole): array
    {
        $this->checkUserRole($userRole);

        $statusActions = $this->findActionsAllowedStatus($this->status);
        $roleActions = $this->findActionsAllowedRole($userRole);

        $allowedActions = array_intersect($statusActions, $roleActions);
        $allowedActions = array_filter($allowedActions, function ($action) use ($userId) {
            return $action::checkRights($userId, $this->performerId, $this->clientId);
        });

        return array_values($allowedActions);
    }

    /**
     * Установка статуса задачи, если переданный статус есть среди допустимых
     * статусов
     * @param string $status - статус задачи
     * @return void
     */
    public function setStatus(string $status): void
    {
        $acceptableStatuses = [
            self::STATUS_NEW,
            self::STATUS_CANCEL,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETE,
            self::STATUS_FAILED
        ];

        in_array($status, $acceptableStatuses)
            ? $this->status = $status
            : throw new ValidStatusException("Статус $status не существует");
    }

    /**
     * Проверяет наличие переданной роли среди доступных.
     * @param string $role - роль пользователя
     * @return void
     */
    public function checkUserRole($role): void
    {
        $acceptableRole = [
            self::ROLE_CLIENT,
            self::ROLE_PERFORMER
        ];

        if (!in_array($role, $acceptableRole)) {
            throw new ValidRoleException("Заданной Вами роли $role не существует");
        }
    }

    /**
     * Поиск доступных действий по переданному статусу
     * @param string $status - статус задачи
     * @return ?array - $массив доступных действий или null
     */
    private function findActionsAllowedStatus($status): ?array
    {
        $map = [
            self::STATUS_NEW => [RespondAction::class, CancelAction::class],
            self::STATUS_IN_PROGRESS => [CompleteAction::class, RefusalAction::class]
        ];

        return $map[$status] ?? null;
    }

    /**
     * Поиск доступных действий по переданному статусу
     * @param string $role - роль пользователя
     * @return ?array - $массив доступных действий или null
     */
    private function findActionsAllowedRole($role): ?array
    {
        $map = [
            self::ROLE_CLIENT => [CancelAction::class, CompleteAction::class],
            self::ROLE_PERFORMER => [RespondAction::class, RefusalAction::class]
        ];

        return $map[$role] ?? null;
    }
}
