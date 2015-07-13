<?php

use yii\db\Migration;
use yii\db\Schema;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'phone' => Schema::TYPE_STRING . ' NOT NULL UNIQUE',
            'email' => Schema::TYPE_STRING . ' NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING . ' UNIQUE',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        $this->createTable(
            '{{%profile}}', [
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL UNIQUE',
            'surname' => Schema::TYPE_STRING . '(50) NULL',
            'name' => Schema::TYPE_STRING . '(50) NULL',
            'middle_name' => Schema::TYPE_STRING . '(50) NULL',
            'birthday' => Schema::TYPE_DATE,
            'gender' => Schema::TYPE_SMALLINT . '(1) NULL COMMENT "1- Мужчина, 2 - Женщина"',
            'avatar_url' => Schema::TYPE_STRING . '(64) NOT NULL DEFAULT ""',
            'balance' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0.00',
            'bonus_balance' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0.00',
            'user_affiliate_id' => Schema::TYPE_INTEGER . ' NULL'
        ], $tableOptions);

        // Foreign Key
        $this->addForeignKey('FK_profile_user', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        // Add first user
        $time = time();
        $password_hash = Yii::$app->security->generatePasswordHash('123456');
        $auth_key = Yii::$app->security->generateRandomString();

        $this->insert('{{%user}}', [
            'id'=>1,
            'phone' => 123456,
            'email' => 'super@admin.com',
            'password_hash' => $password_hash,
            'auth_key' => $auth_key,
            'created_at' => $time,
            'updated_at' => $time
        ]);

        $this->insert('{{%profile}}', [
            'user_id'=>1,
            'name' => 'Superadmin',
            'surname' => 'Site'
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%profile}}');
        $this->dropTable('{{%user}}');
    }

}
