<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `statuses`.
 */
class m230412_015506_add_code_column_to_statuses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'statuses',
            'code',
            $this->string(60)
                ->unique()
                ->notNull()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('code', 'statuses');
    }
}
