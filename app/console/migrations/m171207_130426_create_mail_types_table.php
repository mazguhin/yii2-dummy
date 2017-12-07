<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mail_types`.
 */
class m171207_130426_create_mail_types_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%mail_types}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100)->notNull()->comment('Название типа шаблона'),
            'template' => $this->string(100)->notNull()->comment('Название файла с шаблоном'),
            'title' => $this->string(255)->notNull()->comment('Заголовок письма для этого шаблона'),
            'auto_approve' => "tinyint(1) UNSIGNED DEFAULT 0 COMMENT 'Признак, что письмо будет утверждено по умолчанию'",
            'comment' => $this->string(255)->null()->comment('Пояснения к типу шаблона'),
        ], "COMMENT='Шаблоны писем'");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%mail_types}}');
    }
}
