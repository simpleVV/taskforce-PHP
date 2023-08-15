<?php

use yii\db\Migration;

/**
 * Class m230811_020340_table_auth_table
 */
class m230811_020340_table_auth_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull()->unsigned()
        ]);

        $this->createIndex(
            'auth_1bfk_1',
            'auth',
            'user_id'
        );

        $this->addForeignKey(
            'auth_1bfk_1',
            'auth',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m230811_020340_table_auth_table cannot be reverted.\n";

        return false;
    }
}
