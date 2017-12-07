<?php

use yii\db\Migration;

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
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10)->comment('Статус'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата редактирования'),
            'role' => $this->string(266)->null()->comment('Роль'),
        ], $tableOptions);

        $this->insert('{{%user}}', [
            'username' => 'admin',
            'auth_key' => '',
            'password_hash' => \Yii::$app->security->generatePasswordHash('admin'),
            'email' => 'admin@admin.ru',
            'status' => '1',
            'created_at' => time(),
            'updated_at' => time(),
            'role' => 'Admin',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
