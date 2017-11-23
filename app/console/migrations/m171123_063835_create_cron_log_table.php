<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cron_log`.
 */
class m171123_063835_create_cron_log_table extends Migration
{
    public $tbl = 'cron_log';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tbl, [
            'id'                  => $this->primaryKey()->unsigned(),
            'created_at'          => $this->integer()->unsigned()->notNull()->comment('Дата создания'),
            'process_title'       => $this->string(255)->notNull()->comment('Название процесса'),
            'process_description' => $this->string(255)->notNull()->comment('Описание процесса'),
            'message'             => $this->text()->notNull()->comment('Сообщение'),
            'time'                => $this->decimal(65, 4)->notNull()->comment('Время выполнения')
        ], "COMMENT='Логи процессов'");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->tbl);
    }
}
