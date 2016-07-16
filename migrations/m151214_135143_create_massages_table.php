<?php

use yii\db\Schema;

class m151214_135143_create_massages_table extends \app\modules\main\db\Migration
{

    public $tableName = 'nanson_messenger_messages';

    public function safeUp()
    {

        $this->createTable("{{%$this->tableName}}", [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT now()',
            'author_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'recipient_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'unread' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT true',
            'message' => Schema::TYPE_TEXT . " NOT NULL DEFAULT ''",
        ]);

    }

    public function safeDown()
    {
        $this->dropTable("{{%$this->tableName}}");
    }
}
