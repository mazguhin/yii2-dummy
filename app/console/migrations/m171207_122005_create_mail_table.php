<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mail`.
 */
class m171207_122005_create_mail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%mail}}', [
            'id' => $this->primaryKey()->unsigned(),
            'participant_id' => $this->integer(11)->unsigned()->notNull()->comment('ID анкеты'),
            'message' => $this->text()->notNull()->comment('Сообщение'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
            'updated_at' => $this->integer()->notNull()->comment('Дата редактирования'),
            'sent'  => "tinyint(1) UNSIGNED DEFAULT 0 COMMENT 'Флаг отправлен'",
            'sent_at' => $this->timestamp()->null()->comment('Дата отправки'),
            'title' => $this->string(255)->notNull()->comment('Заголовок'),
            'approved' => "tinyint(1) UNSIGNED DEFAULT 0 COMMENT 'Одобрен'",
            'locked' => "tinyint(1) UNSIGNED DEFAULT 0 COMMENT 'Заблокирован'",
            'type_id' => $this->integer(11)->unsigned()->notNull()->comment('ID типа письма'),
        ], "COMMENT='Письма'");

        $this->createIndex('mail_locked_approved_sent', '{{%mail}}', ['locked', 'approved', 'sent']);
        $this->createIndex('mail_type_id', '{{%mail}}', 'type_id');
        $this->createIndex('participant_id', '{{%mail}}', 'participant_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%mail}}');
    }
}
