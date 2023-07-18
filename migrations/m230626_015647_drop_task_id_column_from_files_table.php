<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `files`.
 */
class m230626_015647_drop_task_id_column_from_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            'files_1bfk_1',
            'files'
        );

        $this->dropIndex(
            'files_1bfk_1',
            'files'
        );

        $this->dropColumn('files', 'task_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230626_015647_task_uid_column_to_files_table cannot be reverted.\n";

        return false;
    }
}
