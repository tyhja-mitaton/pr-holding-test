<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apple}}`.
 */
class m251207_044930_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string()->notNull(),
            'size' => $this->float()->defaultValue(1),
            'on_tree' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer(),
            'fell_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}
