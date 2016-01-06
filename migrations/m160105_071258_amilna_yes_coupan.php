<?php

use yii\db\Schema;
use yii\db\Migration;

class m160105_071258_amilna_yes_coupan extends Migration
{
    public function safeUp()
    {
		$this->createTable($this->db->tablePrefix.'yes_coupon', [
            'id' => 'pk',
            'code' => Schema::TYPE_STRING . '(65) NOT NULL',
            'description' => Schema::TYPE_STRING . '(155) NOT NULL',            
            'price' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'discount' => Schema::TYPE_FLOAT . '',
            'time_from' => Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT NOW()',
            'time_to' => Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT NOW()',            
            'qty' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_SMALLINT. ' NOT NULL DEFAULT 1',
            'time' => Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT NOW()',
            'isdel' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
        ]);
        
        $this->createTable($this->db->tablePrefix.'yes_redeem', [
            'id' => 'pk',            
            'coupon_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'order_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'remarks' => Schema::TYPE_STRING . '(255) NOT NULL',
            'log' => Schema::TYPE_TEXT . '',                        
            'time' => Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT NOW()',
            'isdel' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
        ]);
        $this->addForeignKey( $this->db->tablePrefix.'yes_redeem_coupon_id', $this->db->tablePrefix.'yes_redeem', 'coupon_id', $this->db->tablePrefix.'yes_coupon', 'id', 'RESTRICT', null );
        $this->addForeignKey( $this->db->tablePrefix.'yes_redeem_order_id', $this->db->tablePrefix.'yes_redeem', 'order_id', $this->db->tablePrefix.'yes_order', 'id', 'RESTRICT', null );
    }

    public function safeDown()
    {
        echo "m160105_071258_amilna_yes_coupan cannot be reverted.\n";

        return false;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
