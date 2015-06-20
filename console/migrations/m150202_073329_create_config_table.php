<?php

use yii\db\Schema;
use yii\db\Migration;

class m150202_073329_create_config_table extends Migration
{
    public function safeUp()
    {
        // MySql table options
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        
        //Group table
        $this->createTable(
            '{{%config}}',
            [
                'id' => Schema::TYPE_PK,
                'param' => Schema::TYPE_STRING . '(128) NOT NULL',
                'value' => Schema::TYPE_STRING . '(1000) NOT NULL',
                'default' => Schema::TYPE_STRING . '(1000) NOT NULL',
                'label' => Schema::TYPE_STRING . '(1000) NOT NULL',
                'type' => Schema::TYPE_STRING . '(128) NOT NULL',
                'data' => Schema::TYPE_TEXT . " NOT NULL DEFAULT ''",
            ],
            $tableOptions
        );
        
        $this->createIndex('ux_param', '{{%config}}', 'param', true);
        
        // insert config data
        $this->execute($this->getConfigSql());
    }
    
    public function safeDown()
    {
        $this->dropTable('{{%config}}');
    }
    
     /**
     * @return string SQL to insert first config
     */
    private function getConfigSql()
    {
        return "INSERT INTO {{%config}} (`param`, `value`, `default`, `label`, `type`, `data`) "
        . "VALUES "
                . "('CONTACT.ORGANIZATION_NAME', 'Мой любимый клуб', 'Мой любимый клуб', 'Название организации', 'INPUT_TEXT', ''),"
                . "('CONTACT.PHONE_NUMBER', '+7 (999) 123-45-67', '+7 (999) 123-45-67', 'Контактный номер телефона (отображен на сайте)', 'INPUT_TEXT', ''),"
                . "('CONTACT.EMAIL', 'someone@somewhere.ru', 'someone@somewhere.ru', 'Контактный email', 'INPUT_TEXT', ''),"
                . "('CONTACT.ADDRESS', '<strong>Мой любимый клуб</strong><br />"
                    . "г. Иркутск<br />ул. Ленина 1<br />Второй этаж, офис 2', "
                    . "'<strong>Мой любимый клуб</strong><br />"
                    . "г. Иркутск<br />ул. Ленина 1<br />Второй этаж, офис 2', "
                    . "'Адрес организации', 'INPUT_TEXTAREA', '');";
    }
}
