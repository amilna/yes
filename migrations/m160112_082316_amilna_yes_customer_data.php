<?php

use yii\db\Schema;
use yii\db\Migration;

class m160112_082316_amilna_yes_customer_data extends Migration
{
    public function safeUp()
    {
		$this->addColumn( $this->db->tablePrefix.'yes_customer', 'data' , Schema::TYPE_TEXT . '' );
    }

    public function safeDown()
    {
        echo "m160112_082316_amilna_yes_customer_data cannot be reverted.\n";

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
