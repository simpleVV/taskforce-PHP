<?php

use yii\db\Migration;

/**
 * Handles dropping columns role_id from table `users`.
 */
class m230515_021832_drop_role_id_column_from_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->dropForeignKey(
            'users_1bfk_2',
            'users'
        );

        $this->dropIndex(
            'users_1bfk_2',
            'users'
        );

        $this->dropColumn('users', 'role_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn(
            'role_id',
            'users',
            $this->integer(11)
                ->unsigned()
                ->notNull()
        );

        $this->createIndex(
            'users_1bfk_2',
            'users',
            'role_id'
        );

        $this->addForeignKey(
            'users_1bfk_2',
            'users',
            'id',
            'roles',
            'role_id',
            'CASCADE'
        );
    }
}
