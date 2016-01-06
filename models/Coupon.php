<?php

namespace amilna\yes\models;

use Yii;

/**
 * This is the model class for table "{{%yes_coupon}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $description
 * @property double $price
 * @property double $discount
 * @property string $time_from
 * @property string $time_to
 * @property integer $qty
 * @property integer $status
 * @property string $time
 * @property integer $isdel
 *
 * @property YesRedeem[] $yesRedeems
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yes_coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'description','status'], 'required'],
            [['price', 'discount'], 'string'],
            [['time_from', 'time_to', 'time'], 'safe'],
            [['qty', 'status', 'isdel'], 'integer'],
            [['code'], 'string', 'max' => 65],
            [['description'], 'string', 'max' => 155]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'description' => Yii::t('app', 'Description'),
            'price' => Yii::t('app', 'Price'),
            'discount' => Yii::t('app', 'Discount'),
            'time_from' => Yii::t('app', 'Time From'),
            'time_to' => Yii::t('app', 'Time To'),
            'qty' => Yii::t('app', 'Qty'),
            'status' => Yii::t('app', 'Status'),
            'time' => Yii::t('app', 'Time'),
            'isdel' => Yii::t('app', 'Isdel'),
        ];
    }	
    
	public function itemAlias($list,$item = false,$bykey = false)
	{
		$lists = [
			'status'=>[							
							0=>Yii::t('app','inactive'),							
							1=>Yii::t('app','active'),							
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
    public function getRedeems()
    {
        return $this->hasMany(Redeem::className(), ['coupon_id' => 'id']);
    }
    
	public function beforeSave($insert)
	{		
		if (parent::beforeSave($insert)) {
			$this->time_from = empty($this->time_from)?'1970-01-01 00:00:00':$this->time_from;						
			$this->time_to = empty($this->time_to)?date('Y-m-d H:i:s'):$this->time_to;
			return true;
		} else {
			return false;
		}
	}   
}
