<?php

namespace amilna\yes\models;

use Yii;

/**
 * This is the model class for table "{{%yes_confirmation}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $payment_id
 * @property string $terminal
 * @property string $account
 * @property string $name
 * @property double $amount
 * @property string $remarks
 * @property string $time
 * @property integer $isdel
 *
 * @property YesOrder $order
 * @property YesPayment $payment
 */
class Confirmation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yes_confirmation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'payment_id', 'isdel'], 'integer'],
            [['payment_id', 'terminal', 'account', 'name', 'remarks'], 'required'],
            [['amount'], 'number'],
            [['remarks'], 'string'],
            [['time'], 'safe'],
            [['terminal', 'account', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'payment_id' => Yii::t('app', 'Payment ID'),
            'terminal' => Yii::t('app', 'Terminal'),
            'account' => Yii::t('app', 'Account'),
            'name' => Yii::t('app', 'Name'),
            'amount' => Yii::t('app', 'Amount'),
            'remarks' => Yii::t('app', 'Remarks'),
            'time' => Yii::t('app', 'Time'),
            'isdel' => Yii::t('app', 'Isdel'),
        ];
    }
	
	/* uncomment to undisplay deleted records (assumed the table has column isdel)
	public static function find()
	{
		return parent::find()->where(['{{%yes_confirmation}}.isdel' => 0]);
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
    public function getOrder()
    {
        return $this->hasOne(YesOrder::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(YesPayment::className(), ['id' => 'payment_id']);
    }
}
