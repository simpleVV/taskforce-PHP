<?php

namespace taskforce\logic;

use DateTime;
use taskforce\logic\actions\AbstractAction;
use taskforce\logic\actions\CancelAction;
use taskforce\logic\actions\CompleteAction;
use taskforce\logic\actions\RefusalAction;
use taskforce\logic\actions\RespondAction;
use taskforce\utils\exception\RoleValidException;
use taskforce\utils\exception\StatusValidException;

class TaskManager
{
    const STATUS_NEW = 'new';
    const STATUS_CANCEL = 'cancelled';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETE = 'complete';
    const STATUS_FAILED = 'failed';
    const STATUS_OVERDUE = 'overdue';

    const ROLE_PERFORMER = 'performer';
    const ROLE_CLIENT = 'client';

    private ?int $performerId;
    private int $clientId;
    private string $status;

    /**
     * @param string $status task status
     * @param int $clientId client Id  
     * @param int|null $performerId performer Id 
     * @return void
     */
    public function __construct(string $status, int $clientId, ?int $performerId)
    {
        $this->performerId = $performerId;
        $this->clientId = $clientId;

        $this->setStatus($status);
    }

    /**
     * Returns an array of task statuses, where the key is the 
     * internal name and the value is status names in Russian
     * 
     * @return array array of statuses
     */
    public function getStatusesMap(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCEL => 'Отменено',
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETE => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }

    /**
     * Returns the next status of the task after the completed action
     *   
     * @param AbstractAction $action completed action 
     * @return ?string task status or null if there is no status for the completed action
     */
    public function getNextStatus(AbstractAction $action): ?string
    {
        $map = [
            CompleteAction::class => self::STATUS_COMPLETE,
            CancelAction::class => self::STATUS_CANCEL,
            RefusalAction::class => self::STATUS_FAILED,
        ];

        return $map[get_class($action)] ?? null;
    }

    /**
     * Returns available actions for the specified status and role
     * 
     * @param int $userId current user id
     * @param string $userRole user role
     * @return array array available actions
     */
    public function getAvailableActions(int $userId, string $userRole): array
    {
        $this->checkUserRole($userRole);

        $statusActions = $this->findActionsAllowedStatus($this->status);
        $roleActions = $this->findActionsAllowedRole($userRole);
        $allowedActions = [];

        if ($statusActions && $roleActions) {
            $allowedActions = array_intersect($statusActions, $roleActions);
            $allowedActions = array_filter($allowedActions, function ($action) use ($userId) {
                return $action::checkRights($userId, $this->performerId, $this->clientId);
            });
        }

        return array_values($allowedActions);
    }

    /**
     * Setting the task status if the transferred status is 
     * among the acceptable statuses
     * 
     * @param string $status task status
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
            : throw new StatusValidException('Статус $status не существует');
    }

    /**
     * Checks for the presence of the transferred role among the available.
     * 
     * @param string $role user role
     * @return void
     */
    public function checkUserRole($role): void
    {
        $acceptableRole = [
            self::ROLE_CLIENT,
            self::ROLE_PERFORMER
        ];

        if (!in_array($role, $acceptableRole)) {
            throw new RoleValidException('Заданной Вами роли $role не существует');
        }
    }

    /**
     * Search for available actions based on the transferred status
     * 
     * @param string $status task status
     * @return ?array array of available actions or null
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
     * Search for available actions based on the transferred role
     * 
     * @param string $role user role
     * @return ?array array of available actions or null
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
