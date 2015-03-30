<?php

use yii\db\Schema;
use yii\db\Migration;

class m150330_223353_amilna_yes_sku extends Migration
{
    public function safeUp()
    {
		$this->addColumn( $this->db->tablePrefix.'yes_product', 'sku', Schema::TYPE_STRING . '(65)' );
    }

    public function safeDown()
    {
        echo "m150330_223353_amilna_yes_sku cannot be reverted.\n";

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
