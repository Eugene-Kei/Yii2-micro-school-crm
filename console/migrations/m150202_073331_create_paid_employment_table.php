<?php

use yii\db\Schema;
use yii\db\Migration;

class m150202_073331_create_paid_employment_table extends Migration {

    public function safeUp() {
        // MySql table options
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        //Group table
        $this->createTable(
                '{{%paid_employment}}', [
            'id' => Schema::TYPE_PK,
            'date' => Schema::TYPE_DATE . ' NOT NULL',
            'pay_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'timetable_id' => Schema::TYPE_INTEGER . ' NULL',
                ], $tableOptions
        );

        $this->createIndex('ix_date', '{{%paid_employment}}', 'date');
        
        // Foreign Keys
        $this->addForeignKey('FK_paid_employment_pay_id', '{{%paid_employment}}', 'pay_id', '{{%pay}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_paid_employment_timetable_id', '{{%paid_employment}}', 'timetable_id', '{{%timetable}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown() {
        $this->dropTable('{{%paid_employment}}');
    }

}
