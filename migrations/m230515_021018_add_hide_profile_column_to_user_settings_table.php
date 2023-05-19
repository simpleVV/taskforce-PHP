<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `user_settings`.
 */
class m230515_021018_add_hide_profile_column_to_user_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'user_settings',
            'hide_profile',
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
        $this->dropColumn('user_settings', 'hide_profile');
    }
}
