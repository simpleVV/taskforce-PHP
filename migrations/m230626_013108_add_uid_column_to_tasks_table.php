<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `tasks`.
 */
class m230626_013108_add_uid_column_to_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'tasks',
            'uid',
            $this->char(64)->unique()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210423_152627_file_uid cannot be reverted.\n";

        return false;
    }
}
