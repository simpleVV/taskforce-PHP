<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_categories`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `category`
 */

class m230518_003004_create_user_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_categories', [
            'id' => $this->primaryKey()
                ->unsigned()
                ->notNull(),
            'user_id' => $this->integer(11)
                ->unsigned()
                ->notNull(),
            'category_id' => $this->integer(11)
                ->unsigned()
                ->notNull()
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'user_categories_1bfk_1',
            'user_categories',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'user_categories_1bfk_1',
            'user_categories',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            'user_categories_1bfk_2',
            'user_categories',
            'category_id'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'user_categories_1bfk_2',
            'user_categories',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230518_003004_create_user_categories_table cannot be reverted.\n";

        return false;
    }
}
