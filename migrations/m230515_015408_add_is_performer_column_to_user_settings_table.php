<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `user_settings`.
 */
class m230515_015408_add_is_performer_column_to_user_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'user_settings',
            'is_performer',
            $this->tinyInteger(1)
                ->unsigned()
                ->defaultValue(0)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_settings', 'is_performer');
    }
}
