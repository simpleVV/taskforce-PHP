<?php

require_once 'vendor/autoload.php';

use taskforce\logic\Task;

ini_set('assert.exception', 1);
// ini_set('zend.assertions', 1);

function assert_failure($file, $line, $assertion, $message)
{
    echo "Проверка $assertion в $file на строке $line провалена: $message";
};

$performerId = 81;
$clientId = 234;
$status = 'new';

$task = new Task($status, $clientId, $performerId);

//Test Task class getNextStatus method
var_dump(assert($task->getNextStatus(Task::ACTION_CANCEL) == Task::STATUS_CANCEL, 'cancel action'));
var_dump(assert($task->getNextStatus(Task::ACTION_COMPLETE) == Task::STATUS_COMPLETE, 'complete action'));
var_dump(assert($task->getNextStatus(Task::ACTION_REFUSAL) == Task::STATUS_CANCEL, 'cancel action'));
var_dump(assert($task->getNextStatus('action_unknown') == null, 'no status'));

//Test Task class getActionsMap method
$action_map = $task->getActionsMap();
var_dump(assert($action_map[Task::ACTION_RESPOND] == 'Откликнуться'));
var_dump(assert($action_map[Task::ACTION_CANCEL] == 'Отменить'));
var_dump(assert($action_map[Task::ACTION_REFUSAL] == 'Отказаться'));
var_dump(assert($action_map[Task::ACTION_COMPLETE] == 'Отказаться'));

//Test Task class getStatusMap method
$status_map = $task->getStatusesMap();
var_dump(assert($status_map[Task::STATUS_NEW] == 'Новое'));
var_dump(assert($status_map[Task::STATUS_CANCEL] == 'Отменено'));
var_dump(assert($status_map[Task::STATUS_IN_PROGRESS] == 'В работе'));
var_dump(assert($status_map[Task::STATUS_COMPLETE] == 'Выполнено'));
var_dump(assert($status_map[Task::STATUS_FAILED] == 'Провалено'));
