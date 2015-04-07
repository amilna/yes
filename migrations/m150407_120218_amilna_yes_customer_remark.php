<?php

use yii\db\Schema;
use yii\db\Migration;

class m150407_120218_amilna_yes_customer_remark extends Migration
{
    public function safeUp()
    {
		$this->addColumn( $this->db->tablePrefix.'yes_customer', 'remarks' , Schema::TYPE_TEXT . '' );
    }

    public function safeDown()
    {
        echo "m150407_120218_amilna_yes_customer_remark cannot be reverted.\n";

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
