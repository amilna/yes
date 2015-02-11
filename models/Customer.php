<?php

namespace amilna\yes\models;

use Yii;

/**
 * This is the model class for table "{{%yes_customer}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $phones
 * @property string $addresses
 * @property string $email
 * @property string $last_time
 * @property integer $last_action
 * @property integer $isdel
 *
 * @property YesOrder[] $yesOrders
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yes_customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phones', 'addresses'], 'required'],
            [['phones', 'addresses'], 'string'],
            [['last_time'], 'safe'],
            [['last_action', 'isdel'], 'integer'],
            [['name', 'email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'phones' => Yii::t('app', 'Phones'),
            'addresses' => Yii::t('app', 'Addresses'),
            'email' => Yii::t('app', 'Email'),
            'last_time' => Yii::t('app', 'Last Time'),
            'last_action' => Yii::t('app', 'Last Action'),
            'isdel' => Yii::t('app', 'Isdel'),
        ];
    }
	
	/* uncomment to undisplay deleted records (assumed the table has column isdel) */
	public static function find()
	{
		return parent::find()->where(['{{%yes_customer}}.isdel' => 0]);
	}
	
    
	public function itemAlias($list,$item = false,$bykey = false)
	{
		$lists = [
			/* example list of item alias for a field with name field */
			'last_action'=>[							
							0=>Yii::t('app','say Hi / asking product'),							
							1=>Yii::t('app','make order request'),
							2=>Yii::t('app','buy product'),							
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
    public function getYesOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }
}
