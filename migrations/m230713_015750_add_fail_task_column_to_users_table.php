<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `users`.
 */
class m230713_015750_add_fail_task_column_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'users',
            'fail_tasks',
            $this->integer()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230713_015750_add_fail_task_column_to_users_table cannot be reverted.\n";

        return false;
    }
}
