<?php

use yii\db\Migration;

/**
 * Class m230918_105042_settings
 */
class m230918_105042_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%setting}}', [
            'id' => $this->primaryKey(),
            'section' => $this->string()->notNull(),
            'key' => $this->string()->notNull(),
            'value' => $this->text()->notNull(),
            'status' => $this->boolean()->defaultValue(1),
            'description' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->alterColumn('{{%setting}}', 'value', 'TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL');
        $this->createIndex( 'section_key', '{{%setting}}', ['section', 'key'], true );

        $this->createTable('{{%setting_translation}}', [
            'model_id' => $this->integer()->notNull(),
            'language' => $this->string(5)->notNull(),
            'value' => $this->text()->notNull(),
        ], $tableOptions);

        $this->alterColumn('{{%setting_translation}}', 'value', 'TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL');
        $this->createIndex('model_id_language', '{{%setting_translation}}', ['model_id', 'language'], true );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%setting}}');
        $this->dropTable('{{%setting_translation}}');
    }
    
}
