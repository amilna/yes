<?php

namespace amilna\yes\models;

use Yii;

/**
 * This is the model class for table "{{%yes_sale}}".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $order_id
 * @property string $data
 * @property double $amount
 * @property string $quantity
 * @property string $time
 * @property integer $isdel
 *
 * @property YesProduct $product
 * @property YesOrder $order
 */
class Sale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yes_sale}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'order_id'], 'required'],
            [['product_id', 'order_id', 'isdel'], 'integer'],
            [['data'], 'string'],
            [['amount', 'quantity'], 'number'],
            [['time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'data' => Yii::t('app', 'Data'),
            'amount' => Yii::t('app', 'Amount'),
            'quantity' => Yii::t('app', 'Quantity'),
            'time' => Yii::t('app', 'Time'),
            'isdel' => Yii::t('app', 'Isdel'),
        ];
    }
	
	/* uncomment to undisplay deleted records (assumed the table has column isdel)
	public static function find()
	{
		return parent::find()->where(['{{%yes_sale}}.isdel' => 0]);
	}
	*/
    
	public function itemAlias($list,$item = false,$bykey = false)
	{
		$lists = [
			/* example list of item alias for a field with name field
			'afield'=>[							
							0=>Yii::t('app','an alias of 0'),							
							1=>Yii::t('app','an alias of 1'),														
						],			
			*/			
		];				
		
		if (isset($lists[$list]))
		{					
			if ($bykey)
			{				
				$nlist = [];
				foreach ($lists[$list] as $k=>$i)
				{
					$nlist[$i] = $k;
				}
				$list = $nlist;				
			}
			else
			{
				$list = $lists[$list];
			}
							
			if ($item !== false)
			{			
				return	(isset($list[$item])?$list[$item]:false);
			}
			else
			{
				return $list;	
			}			
		}
		else
		{
			return false;	
		}
	}    
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(YesProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(YesOrder::className(), ['id' => 'order_id']);
    }
}
