<?php

use yii\db\Schema;
use yii\db\Migration;

class m150212_091226_amilna_yes extends Migration
{
    public function up()
    {
		$this->createTable($this->db->tablePrefix.'yes_category', [
            'id' => 'pk',            
            'title' => Schema::TYPE_STRING . '(65) NOT NULL',
            'parent_id' => Schema::TYPE_INTEGER,
            'description' => Schema::TYPE_TEXT.' NOT NULL',
            'image' => Schema::TYPE_STRING.'',
            'status' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT TRUE',
            'isdel' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
        ]);
        $this->createIndex($this->db->tablePrefix.'yes_category_title'.'_key', $this->db->tablePrefix.'yes_category', 'title', true);        
        $this->addForeignKey( $this->db->tablePrefix.'yes_category_parent_id', $this->db->tablePrefix.'yes_category', 'parent_id', $this->db->tablePrefix.'yes_category', 'id', 'SET NULL', null );
        
        $this->createTable($this->db->tablePrefix.'yes_product', [
            'id' => 'pk',
            'title' => Schema::TYPE_STRING . '(65) NOT NULL',
            'description' => Schema::TYPE_STRING . '(155) NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
            'price' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'discount' => Schema::TYPE_FLOAT . '',
            'data' => Schema::TYPE_TEXT . ' NOT NULL',
            'tags' => Schema::TYPE_STRING . '',
            'images' => Schema::TYPE_TEXT . '',            
            'author_id' => Schema::TYPE_INTEGER,
            'isfeatured' => Schema::TYPE_BOOLEAN.' NOT NULL DEFAULT TRUE',
            'status' => Schema::TYPE_SMALLINT. ' NOT NULL DEFAULT 1',
            'time' => Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT NOW()',
            'isdel' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
        ]);
        $this->addForeignKey( $this->db->tablePrefix.'yes_product_author_id', $this->db->tablePrefix.'yes_product', 'author_id', $this->db->tablePrefix.'user', 'id', 'SET NULL', null );
        
        $this->createTable($this->db->tablePrefix.'yes_cat_pro', [                        
            'category_id' => Schema::TYPE_INTEGER. ' NOT NULL',
            'product_id' => Schema::TYPE_INTEGER. ' NOT NULL',            
            'isdel' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
        ]);
        $this->addForeignKey( $this->db->tablePrefix.'yes_cat_pro_categroy_id', $this->db->tablePrefix.'yes_cat_pro', 'category_id', $this->db->tablePrefix.'yes_category', 'id', 'CASCADE', null );
        $this->addForeignKey( $this->db->tablePrefix.'yes_cat_pro_product_id', $this->db->tablePrefix.'yes_cat_pro', 'product_id', $this->db->tablePrefix.'yes_product', 'id', 'CASCADE', null );
        
        $this->createTable($this->db->tablePrefix.'yes_customer', [
            'id' => 'pk',            
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'phones' => Schema::TYPE_TEXT . ' NOT NULL',
            'addresses' => Schema::TYPE_TEXT . ' NOT NULL',
            'email' => Schema::TYPE_STRING. '',
            'last_time' => Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT NOW()',
            'last_action' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
            'isdel' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
        ]);
        
        $this->createTable($this->db->tablePrefix.'yes_order', [
            'id' => 'pk',
            'customer_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'reference' => Schema::TYPE_STRING . ' NOT NULL',
            'total' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'data' => Schema::TYPE_TEXT . ' NOT NULL',            
            'status' => Schema::TYPE_SMALLINT. ' NOT NULL DEFAULT 0',
            'time' => Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT NOW()',            
            'complete_reference' => Schema::TYPE_STRING . ' ',
            'complete_time' => Schema::TYPE_TIMESTAMP. ' ',
            'log' => Schema::TYPE_TEXT . ' NOT NULL',
            'isdel' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
        ]);
        $this->addForeignKey( $this->db->tablePrefix.'yes_order_customer_id', $this->db->tablePrefix.'yes_order', 'customer_id', $this->db->tablePrefix.'yes_customer', 'id', 'RESTRICT', null );
        
        $this->createTable($this->db->tablePrefix.'yes_sale', [
            'id' => 'pk',
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'order_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'data' => Schema::TYPE_TEXT . '',            
            'amount' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'quantity' => Schema::TYPE_DECIMAL . '(15,6) NOT NULL DEFAULT 0',
            'time' => Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT NOW()',
            'isdel' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
        ]);
        $this->addForeignKey( $this->db->tablePrefix.'yes_sale_product_id', $this->db->tablePrefix.'yes_sale', 'product_id', $this->db->tablePrefix.'yes_product', 'id', 'RESTRICT', null );
        $this->addForeignKey( $this->db->tablePrefix.'yes_sale_order_id', $this->db->tablePrefix.'yes_sale', 'order_id', $this->db->tablePrefix.'yes_order', 'id', 'RESTRICT', null );
        
        $this->createTable($this->db->tablePrefix.'yes_payment', [
            'id' => 'pk',            
            'terminal' => Schema::TYPE_STRING . ' NOT NULL',
            'account' => Schema::TYPE_STRING . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT. ' NOT NULL DEFAULT 1',
            'isdel' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
        ]);
        
        $this->createTable($this->db->tablePrefix.'yes_confirmation', [
            'id' => 'pk',
            'order_id' => Schema::TYPE_INTEGER . '',
            'payment_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'terminal' => Schema::TYPE_STRING . ' NOT NULL',
            'account' => Schema::TYPE_STRING . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'amount' => Schema::TYPE_FLOAT . ' NOT NULL DEFAULT 0',
            'remarks' => Schema::TYPE_TEXT . ' NOT NULL',
            'time' => Schema::TYPE_TIMESTAMP. ' NOT NULL DEFAULT NOW()',
            'isdel' => Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0',
        ]);
        $this->addForeignKey( $this->db->tablePrefix.'yes_confirmation_order_id', $this->db->tablePrefix.'yes_confirmation', 'order_id', $this->db->tablePrefix.'yes_order', 'id', 'SET NULL', null );
        $this->addForeignKey( $this->db->tablePrefix.'yes_confirmation_payment_id', $this->db->tablePrefix.'yes_confirmation', 'payment_id', $this->db->tablePrefix.'yes_payment', 'id', 'RESTRICT', null );
    }

    public function down()
    {
        echo "m150212_091226_amilna_yes cannot be reverted.\n";

        return false;
    }
}
