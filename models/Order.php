<?php

namespace amilna\yes\models;

use Yii;

/**
 * This is the model class for table "{{%yes_order}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $reference
 * @property double $total
 * @property string $data
 * @property integer $status
 * @property string $time
 * @property string $complete_reference
 * @property string $complete_time
 * @property string $log
 * @property integer $isdel
 *
 * @property YesConfirmation[] $yesConfirmations
 * @property YesCustomer $customer
 * @property YesSale[] $yesSales
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yes_order}}';
    }
			
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'reference', 'data', 'log'], 'required'],
            [['status', 'isdel'], 'integer'],
            [['total'], 'number'],
            [['data', 'log'], 'string'],
            [['time', 'complete_time'], 'safe'],
            [['reference', 'complete_reference'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'reference' => Yii::t('app', 'Reference'),
            'total' => Yii::t('app', 'Total'),
            'data' => Yii::t('app', 'Data'),            
            'status' => Yii::t('app', 'Status'),
            'time' => Yii::t('app', 'Time'),
            'complete_reference' => Yii::t('app', 'Complete Reference'),
            'complete_time' => Yii::t('app', 'Complete Time'),
            'log' => Yii::t('app', 'Log'),
            'isdel' => Yii::t('app', 'Isdel'),
        ];
    }
	
	/* uncomment to undisplay deleted records (assumed the table has column isdel) */
	public static function find()
	{
		return parent::find()->where(['{{%yes_order}}.isdel' => 0]);
	}
	
    
	public function itemAlias($list,$item = false,$bykey = false)
	{
		$lists = [
			/* example list of item alias for a field with name field */
			'status'=>[							
							0=>Yii::t('app','order request'),							
							1=>Yii::t('app','payment accepted'),
							2=>Yii::t('app','shipping product'),
							3=>Yii::t('app','order completed'),
							4=>Yii::t('app','make complain'),
						],			
						
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
    public function getConfirmations()
    {
        return $this->hasMany(Confirmation::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::className(), ['order_id' => 'id']);
    }
}
