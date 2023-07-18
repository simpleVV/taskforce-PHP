<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `responses`.
 */
class m230628_013018_add_is_deny_column_to_responses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'responses',
            'is_deny',
            $this->tinyInteger()
                ->defaultValue(0)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230628_013018_add_is_deny_column_to_responses_table cannot be reverted.\n";

        return false;
    }
}
