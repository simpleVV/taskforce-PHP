<?php

use yii\db\Migration;

/**
 * Class m230626_015139_task_uid_column_to_files_table
 */
class m230626_015139_task_uid_column_to_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('files', 'task_uid', $this->char(64));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230626_015139_task_uid_column_to_files_table cannot be reverted.\n";

        return false;
    }
}
