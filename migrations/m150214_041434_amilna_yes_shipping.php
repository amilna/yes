<?php

use yii\db\Schema;
use yii\db\Migration;

class m150214_041434_amilna_yes_shipping extends Migration
{
    public function up()
    {
		$this->createTable($this->db->tablePrefix.'yes_shipping', [
            'id' => 'pk',            
            'code' => Schema::TYPE_STRING . ' NOT NULL',
            'city' => Schema::TYPE_STRING . ' NOT NULL',
            'area' => Schema::TYPE_STRING . ' NOT NULL',
            'data' => Schema::TYPE_TEXT . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT. ' NOT NULL DEFAULT 1',
            'isdel' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
        ]);
        $this->createIndex($this->db->tablePrefix.'yes_shipping_code'.'_key', $this->db->tablePrefix.'yes_shipping', 'code', true);
    }

    public function down()
    {
        echo "m150214_041434_amilna_yes_shipping cannot be reverted.\n";

        return false;
    }
}
