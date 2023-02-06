<?php

class Task
{
  const STATUS_NEW = 'new';
  const STATUS_CANCEL = 'cancel';
  const STATUS_IN_PROGRESS = 'in progress';
  const STATUS_COMPLETE = 'complete';
  const STATUS_FAILED = 'failed';

  const ACTION_RESPOND = 'task_respond';
  const ACTION_CANCEL = 'task_cancel';
  const ACTION_REFUSAL = 'task_refusal';
  const ACTION_COMPLETE = 'task_complete';

  const ROLE_PERFORMER = 'performer';
  const ROLE_CLIENT = 'client';

  private ?int $performerId;
  private int $clientId;
  private $status;

  public function __construct(string $status, ?int $performerId, ?int $clientId)
  {
    // $this->setStatus($status);
    $this->performerId = $performerId;
    $this->clientId = $clientId;
  }

  public function getStatusesMap()
  {
    return [
      self::STATUS_NEW => 'Новое',
      self::STATUS_CANCEL => 'Отменено',
      self::STATUS_IN_PROGRESS => 'В работе',
      self::STATUS_COMPLETE => 'Выполнено',
      self::STATUS_FAILED => 'Провалено'
    ];
  }

  public function getActionsMap()
  {
    return [
      self::ACTION_RESPOND => 'Откликнуться',
      self::ACTION_CANCEL => 'Отменить',
      self::ACTION_REFUSAL => 'Отказаться',
      self::ACTION_COMPLETE => 'Выполнено'
    ];
  }

  public function getNextStatus(string $action)
  {
    $map = [
      self::ACTION_COMPLETE => self::STATUS_COMPLETE,
      self::ACTION_CANCEL => self::STATUS_CANCEL,
      self::ACTION_REFUSAL => self::STATUS_CANCEL,
    ];

    return $map[$action] ?? null;
  }

  private function getAvailableActions(string $status)
  {
    self::STATUS_IN_PROGRESS =>
  }
}
