<?php

namespace taskforce\logic;

class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCEL = 'cancel';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETE = 'complete';
    const STATUS_FAILED = 'fail';

    const ACTION_RESPOND = 'action_respond';
    const ACTION_CANCEL = 'action_cancel';
    const ACTION_REFUSAL = 'action_refusal';
    const ACTION_COMPLETE = 'action_complete';

    const ROLE_PERFORMER = 'performer';
    const ROLE_CLIENT = 'client';

    private ?int $performerId;
    private int $clientId;
    private $status;

    /**
     * @param string $status - статус задачи
     * @param int $clientId - идентификатор заказчика  
     * @param int|null $performerId - идентификатор исполнителя 
     * @return void
     */

    public function __construct(string $status, int $clientId, ?int $performerId = null)
    {
        $this->setStatus($status);
        $this->performerId = $performerId;
        $this->clientId = $clientId;
    }

    /**
     * массив действий, где ключ — внутреннее имя, а значение — 
     * названия действия на русском
     * @return array - массив действий
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
     * массив статусов, где ключ — внутреннее имя, а значение — 
     * названия статуса на русском
     * @return array - массив статусов
     */
    public function getActionsMap(): array
    {
        return [
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_REFUSAL => 'Отказаться',
            self::ACTION_COMPLETE => 'Отказаться'
        ];
    }

    /**
     * Возвращает следующий статус задачи после выполненного действия  
     * @param string $action - выполненное действие 
     * @return array|null - статус задания или null если нет статуса для выполненного действия
     */
    public function getNextStatus(string $action): ?string
    {
        $map = [
            self::ACTION_COMPLETE => self::STATUS_COMPLETE,
            self::ACTION_CANCEL => self::STATUS_CANCEL,
            self::ACTION_REFUSAL => self::STATUS_CANCEL,
        ];

        return $map[$action] ?? null;
    }

    /**
     * Возвращает доступные действия для указанного статуса 
     * @param string $status - статус задачи
     * @return array - массив доступных действия для переданного статуса
     */
    private function getAvailableActions(string $status): array
    {
        $map = [
            self::STATUS_NEW => [self::ACTION_CANCEL, self::ACTION_RESPOND],
            self::STATUS_IN_PROGRESS => [self::ACTION_COMPLETE, self::ACTION_REFUSAL]
        ];

        return $map[$status] ?? [];
    }

    /**
     * Установка статуса задачи, если переданный статус есть среди допустимых
     * статусов
     * @param string $status - статус задачи
     * @return void
     */
    private function setStatus(string $status): void
    {
        $acceptableStatuses = [
            self::STATUS_NEW,
            self::STATUS_CANCEL,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETE,
            self::STATUS_FAILED
        ];

        if (in_array($status, $acceptableStatuses)) {
            $this->status = $status;
        }
    }
}
