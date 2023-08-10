<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `tasks`.
 */
class m230807_021710_add_long_column_to_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'tasks',
            'long',
            $this->float()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230807_021710_add_long_column_to_tasks_table.\n";

        return false;
    }
}
