<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `statuses`.
 */
class m230711_022300_add_action_column_to_statuses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'statuses',
            'action',
            $this->char(64)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230711_022300_add_action_column_to_statuses_table cannot be reverted.\n";

        return false;
    }
}
