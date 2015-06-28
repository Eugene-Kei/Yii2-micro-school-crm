<?php

use yii\db\Schema;
use yii\db\Migration;

class m150202_073326_create_group_table extends Migration
{
    public function safeUp()
    {
        // MySql table options
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        
        //Group table
        $this->createTable(
            '{{%group}}',
            [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . '(80) NOT NULL',
                'description' => Schema::TYPE_STRING . '(255) NULL',
                'status' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT "0"',
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%group}}');
    }
}
