<?php

use yii\db\Schema;
use yii\db\Migration;

class m150202_073330_create_pay_table extends Migration {

    public function safeUp() {
        // MySql table options
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        //Group table
        $this->createTable(
                '{{%pay}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'ticket_id' => Schema::TYPE_INTEGER . ' NULL',
            'comment' => Schema::TYPE_STRING . '(255) NULL',
            'current_cost' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0.00',
            'cash' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0.00',
            'bonus_cash' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0.00',
            'create_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
                ], $tableOptions
        );
        
        // Foreign Keys
        $this->addForeignKey('FK_pay_ticket_id', '{{%pay}}', 'ticket_id', '{{%season_ticket}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('FK_pay_user_id', '{{%pay}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        
        // insert config data
        $this->batchInsert('{{%config}}', ['param', 'value', 'default', 'label', 'type', 'data'], [
           ['AFFILIATE.IS_ACTIVE', 0, 0, 'Партнерская программа', 'INPUT_SWITCH', serialize([0=>'OFF', 1=>'ON'])],
           ['AFFILIATE.PERCENT', 10, 10, 'Партнерская комиссия первого уровня (%)', 'INPUT_TEXT', ''],
           ['AFFILIATE.PERCENT_SECOND_LEVEL', 0, 0, 'Партнерская комиссия второго уровня (%)', 'INPUT_TEXT', ''],
           ['AFFILIATE.MIN_SUM', 0, 0, 'Минимальная сумма платежа, для партнерских отчислений', 'INPUT_TEXT', ''],
        ]);
    }

    public function safeDown() {
        $this->dropTable('{{%pay}}');

        //delete configuration
        $this->delete('{{%config}}', ['param' => [
            'AFFILIATE.IS_ACTIVE',
            'AFFILIATE.PERCENT',
            'AFFILIATE.PERCENT_SECOND_LEVEL',
            'AFFILIATE.MIN_SUM',
        ]]);
    }

}
