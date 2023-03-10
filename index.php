<?php

require_once 'vendor/autoload.php';

use taskforce\logic\actions\CancelAction;
use taskforce\logic\actions\CompleteAction;
use taskforce\logic\actions\RefusalAction;
use taskforce\logic\Task;

ini_set('assert.exception', 1);
// ini_set('zend.assertions', 1);

$performerId = 81;
$clientId = 234;
$status = 'new';
$finishDate = strtotime('2023/05/30');

$cancelAction = new CancelAction();
$completeAction = new CompleteAction();
$refusalAction = new RefusalAction();

$task = new Task($status, $clientId, $performerId);

//Test Task class getNextStatus method
var_dump("cancel action", $task->getNextStatus(CancelAction::getInnerName()) == Task::STATUS_CANCEL);
var_dump("complete action", $task->getNextStatus(CompleteAction::getInnerName()) == Task::STATUS_COMPLETE);
var_dump("act_refusal", $task->getNextStatus(RefusalAction::getInnerName()) == Task::STATUS_CANCEL);
// var_dump("no status", $task->getNextStatus(null) == null);

//Test Task class getStatusMap method
$status_map = $task->getStatusesMap();
var_dump("status new", $status_map[Task::STATUS_NEW] == "Новое");
var_dump("status cancel", $status_map[Task::STATUS_CANCEL] == "Отменено");
var_dump("in_progress", $status_map[Task::STATUS_IN_PROGRESS] == "В работе");
var_dump("complete", $status_map[Task::STATUS_COMPLETE] == "Выполнено");
var_dump("fail", $status_map[Task::STATUS_FAILED] == "Провалено");

//Test Task class getAvailableActions method
var_dump('new task-> client -> cancel task', $task->getAvailableActions(234, Task::ROLE_CLIENT));
var_dump('new task-> performer -> respond task', $task->getAvailableActions(81, Task::ROLE_PERFORMER));

$task->setStatus(Task::STATUS_IN_PROGRESS);

var_dump('in progress task-> client -> complete task', $task->getAvailableActions(234, Task::ROLE_CLIENT));
var_dump('it progress task -> performer -> refusal task', $task->getAvailableActions(81, Task::ROLE_PERFORMER));
