<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `reviews`.
 */
class m230706_064125_add_task_id_column_to_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn(
            'reviews',
            'task_id',
            $this->integer(11)
                ->unsigned()
                ->notNull()
        );

        $this->createIndex(
            'reviews_1bfk_3',
            'reviews',
            'task_id'
        );

        $this->addForeignKey(
            'reviews_1bfk_3',
            'reviews',
            'task_id',
            'tasks',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230706_064125_add_task_id_column_to_reviews_table cannot be reverted.\n";

        return false;
    }
}
