<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `roles`.
 */
class m230518_002347_drop_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('roles');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('roles', [
            'id' => $this->primaryKey(),
            'name' => $this->varchar(60)->notNull(),
            'code' => $this->varchar(60)->unique()->notNull(),
        ]);
    }
}
