<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `files`.
 */
class m230519_053050_drop_user_id_column_from_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->dropForeignKey(
            'files_1bfk_2',
            'files'
        );

        $this->dropIndex(
            'files_1bfk_2',
            'files'
        );

        $this->dropColumn('files', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn(
            'user_id',
            'files',
            $this->integer(11)
                ->unsigned()
                ->notNull()
        );

        $this->createIndex(
            'files_1bfk_2',
            'files',
            'user_id'
        );

        $this->addForeignKey(
            'files_1bfk_2',
            'files',
            'id',
            'users',
            'user_id',
            'CASCADE'
        );
    }
}
