<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `tasks`.
 */
class m230807_022249_add_city_id_column_to_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'tasks',
            'city_id',
            $this->integer(11)
                ->unsigned()
        );

        $this->createIndex(
            'tasks_1bfk_5',
            'tasks',
            'city_id'
        );

        $this->addForeignKey(
            'tasks_1bfk_5',
            'tasks',
            'city_id',
            'cities',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
