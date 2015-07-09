<?php

use yii\db\Schema;
use yii\db\Migration;

class m150202_073328_create_season_ticket_table extends Migration
{
    public function safeUp()
    {
        // MySql table options
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        
        //timetable table
        $this->createTable(
            '{{%season_ticket}}',
            [
                'id' => Schema::TYPE_PK,
                'title' => Schema::TYPE_STRING. '(80) NOT NULL',
                'description' => Schema::TYPE_STRING. '(255) NULL',
                'cost' => Schema::TYPE_DECIMAL. '(10,2) NOT NULL DEFAULT 0.00',
                'amount' => Schema::TYPE_INTEGER. " NULL COMMENT 'Количество занятий'",
                'limit_format' => Schema::TYPE_SMALLINT . "(1) NULL COMMENT 'Лимит формат - дни, недели, месяца... '",
                'limit_value' => Schema::TYPE_INTEGER . " NULL COMMENT 'лимит значение - максимальная длительность абонемента (зависит от формата).'",
            ],
            $tableOptions
        );
        
    }

    public function safeDown()
    {
        $this->dropTable('{{%season_ticket}}');
    }
}
