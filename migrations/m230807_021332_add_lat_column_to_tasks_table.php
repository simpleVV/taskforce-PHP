<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `tasks`.
 */
class m230807_021332_add_lat_column_to_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'tasks',
            'lat',
            $this->float()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230807_021332_add_lat_column_to_tasks_table.\n";

        return false;
    }
}
