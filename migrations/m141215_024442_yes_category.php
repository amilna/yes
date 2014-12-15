<?php

use yii\db\Schema;
use yii\db\Migration;

class m141215_024442_yes_category extends Migration
{
    public function up()
    {
		$this->createTable($this->db->tablePrefix.'yes_category', [
            'id' => 'pk',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'picture' => Schema::TYPE_TEXT,
        ]);
    }

    public function down()
    {
        echo "m141215_024442_yes_category cannot be reverted.\n";

        return false;
    }
}
