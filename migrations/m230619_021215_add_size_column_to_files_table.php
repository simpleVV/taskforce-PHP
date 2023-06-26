<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%files}}`.
 */
class m230619_021215_add_size_column_to_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('files', 'size', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210430_081757_file_size cannot be reverted.\n";

        return false;
    }
}
