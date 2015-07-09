<?php

use yii\db\Schema;
use yii\db\Migration;

class m150202_073327_create_timetable_table extends Migration
{
    public function safeUp()
    {
        // MySql table options
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        
        //timetable table
        $this->createTable(
            '{{%timetable}}',
            [
                'id' => Schema::TYPE_PK,
                'start' => Schema::TYPE_TIME,
                'end' => Schema::TYPE_TIME,
                'week_day' => Schema::TYPE_SMALLINT . '(1) NOT NULL',
                'group_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            ],
            $tableOptions
        );
        
        // Foreign Keys
        $this->addForeignKey('FK_timetable_group_id', '{{%timetable}}', 'group_id', '{{%group}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%timetable}}');
    }
}
