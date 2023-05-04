<?php

// require_once 'vendor/autoload.php';
// require_once 'init.php';

// use taskforce\utils\CSVtoSQLConverter;
// use taskforce\utils\exception\FileFormatException;
// use taskforce\utils\exception\SourceFileException;
// use taskforce\utils\ExtensionFileSearcher;

// $searcher = new ExtensionFileSearcher(DIRECTORY_CSV_FILES, 'csv');
// $files = [];

// try {
//     $searcher->findFiles();
//     $files = $searcher->getFiles();
// } catch (SourceFileException $e) {
//     error_log("Не удалось обработать директорию: ", $e->getMessage());
// }

// foreach ($files as $file) {
//     $loader = new CSVtoSQLConverter($file);

//     try {
//         $loader->importCSVToSQL(DIRECTORY_SQL_FILES);
//     } catch (SourceFileException $e) {
//         error_log("Не удалось обработать csv файл: " . $e->getMessage());
//     } catch (FileFormatException $e) {
//         error_log("Неверная форма файла импорта: " . $e->getMessage());
//     }
// }

// use taskforce\logic\actions\CancelAction;
// use taskforce\logic\actions\CompleteAction;
// use taskforce\logic\actions\RefusalAction;
// use taskforce\logic\TaskAvailableActions;

// ini_set('assert.exception', 1);
// // ini_set('zend.assertions', 1);

// $performerId = 81;
// $clientId = 234;
// $status = 'new';
// $finishDate = strtotime('2023/05/30');

// $cancelAction = new CancelAction();
// $completeAction = new CompleteAction();
// $refusalAction = new RefusalAction();

// $task = new TaskAvailableActions($status, $clientId, $performerId);

// //Test TaskAvailableActions class getNextStatus method
// var_dump("cancel action", $task->getNextStatus(CancelAction::getInnerName()) == TaskAvailableActions::STATUS_CANCEL);
// var_dump("complete action", $task->getNextStatus(CompleteAction::getInnerName()) == TaskAvailableActions::STATUS_COMPLETE);
// var_dump("act_refusal", $task->getNextStatus(RefusalAction::getInnerName()) == TaskAvailableActions::STATUS_CANCEL);
// // var_dump("no status", $task->getNextStatus(null) == null);

// //Test TaskAvailableActions class getStatusMap method
// $status_map = $task->getStatusesMap();
// var_dump("status new", $status_map[TaskAvailableActions::STATUS_NEW] == "Новое");
// var_dump("status cancel", $status_map[TaskAvailableActions::STATUS_CANCEL] == "Отменено");
// var_dump("in_progress", $status_map[TaskAvailableActions::STATUS_IN_PROGRESS] == "В работе");
// var_dump("complete", $status_map[TaskAvailableActions::STATUS_COMPLETE] == "Выполнено");
// var_dump("fail", $status_map[TaskAvailableActions::STATUS_FAILED] == "Провалено");

// //Test TaskAvailableActions class getAvailableActions method
// var_dump('new TaskAvailableActions-> client -> cancel task', $task->getAvailableActions(234, TaskAvailableActions::ROLE_CLIENT));
// var_dump('new task-> performer -> respond task', $task->getAvailableActions(81, TaskAvailableActions::ROLE_PERFORMER));

// $task->setStatus(TaskAvailableActions::STATUS_IN_PROGRESS);

// var_dump('in progress task-> client -> complete task', $task->getAvailableActions(234, TaskAvailableActions::ROLE_CLIENT));
// var_dump('it progress task -> performer -> refusal task', $task->getAvailableActions(81, TaskAvailableActions::ROLE_PERFORMER));
