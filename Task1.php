<?php

class Task
{
  const STATUS_NEW = 'new';
  const STATUS_CANCEL = 'cancel';
  const STATUS_IN_PROGRESS = 'in progress';
  const STATUS_COMPLETE = 'complete';
  const STATUS_FAILED = 'fail';

  const ACTION_CANCEL = 'task_cancel';
  const ACTION_RESPOND = 'task_respond';
}
