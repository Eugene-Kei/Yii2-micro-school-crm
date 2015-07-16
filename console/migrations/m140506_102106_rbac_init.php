<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

use yii\base\InvalidConfigException;
use yii\db\Schema;
use yii\rbac\DbManager;

/**
 * Initializes RBAC tables
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @since 2.0
 */
class m140506_102106_rbac_init extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

    public function up()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($authManager->ruleTable, [
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'rule_name' => Schema::TYPE_STRING . '(64)',
            'data' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES ' . $authManager->ruleTable . ' (name) ON DELETE SET NULL ON UPDATE CASCADE',
        ], $tableOptions);
        $this->createIndex('idx-auth_item-type', $authManager->itemTable, 'type');

        $this->createTable($authManager->itemChildTable, [
            'parent' => Schema::TYPE_STRING . '(64) NOT NULL',
            'child' => Schema::TYPE_STRING . '(64) NOT NULL',
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        $this->createTable($authManager->assignmentTable, [
            'item_name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'user_id' => Schema::TYPE_STRING . '(64) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER,
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        //foreign_key_checks off
        $this->execute('SET foreign_key_checks=0');

        //insert assignments
        $this->insert($authManager->assignmentTable,
            ['item_name' => 'superadmin', 'user_id' => '1', 'created_at' => time()]);

        //insert child items
        $this->batchInsert($authManager->itemChildTable, ['parent', 'child'], [
            ['superadmin', '/*'],
            ['director', '/statistics/*'],
            ['director', '/config/*'],
            ['director', '/ticket/*'],
            ['superadmin', '/gii/*'],
            ['admin', '/news/admin/*'],
            ['superadmin', '/rbac/*'],
            ['admin', '/user/*'],
            ['admin', '/employment/*'],
            ['admin', '/pay/*'],
            ['admin', '/group/*'],
            ['admin', '/timetable/*'],
            ['admin', '/site/*'],
            ['user', '/cabinet/*'],
            ['director', 'admin'],
            ['superadmin', 'director'],
            ['admin', 'user']
        ]);

        //insert items
        $this->batchInsert($authManager->itemTable,['name','type','description','rule_name', 'data','created_at','updated_at'],[
            ['/*', 2, NULL, NULL, NULL,time(),time()],
            ['/ticket/*', 2, NULL,NULL,NULL,time(),time()],
            ['/pay/*', 2, NULL,NULL,NULL,time(),time()],
            ['/config/*', 2, NULL,NULL,NULL,time(),time()],
            ['/gii/*', 2, NULL,NULL,NULL,time(),time()],
            ['/news/admin/*', 2, NULL,NULL,NULL,time(),time()],
            ['/rbac/*', 2, NULL,NULL,NULL,time(),time()],
            ['/site/*', 2, NULL,NULL,NULL,time(),time()],
            ['/user/*', 2, NULL,NULL,NULL,time(),time()],
            ['/statistics/*', 2, NULL,NULL,NULL,time(),time()],
            ['/employment/*', 2, NULL,NULL,NULL,time(),time()],
            ['/timetable/*', 2, NULL,NULL,NULL,time(),time()],
            ['/group/*', 2, NULL,NULL,NULL,time(),time()],
            ['/cabinet/*', 2, NULL,NULL,NULL,time(),time()],
            ['admin', 1, 'admin',NULL,NULL,time(),time()],
            ['director', 1, 'director',NULL,NULL,time(),time()],
            ['superadmin', 1, 'superadmin',NULL,NULL,time(),time()],
            ['user', 1, 'user',NULL,NULL,time(),time()],
        ]);

        //foreign_key_checks on
        $this->execute('SET foreign_key_checks=1');
    }

    public function down()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
    }
}
