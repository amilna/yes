<?php

namespace amilna\yes\models;

use Yii;

/**
 * This is the model class for table "{{%yes_payment}}".
 *
 * @property integer $id
 * @property string $terminal
 * @property string $account
 * @property string $name
 * @property integer $status
 * @property integer $isdel
 *
 * @property YesConfirmation[] $yesConfirmations
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yes_payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['terminal', 'account', 'name'], 'required'],
            [['status', 'isdel'], 'integer'],
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
            'terminal' => Yii::t('app', 'Terminal'),
            'account' => Yii::t('app', 'Account'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'isdel' => Yii::t('app', 'Isdel'),
        ];
    }
	
	/* uncomment to undisplay deleted records (assumed the table has column isdel) */
	public static function find()
	{
		return parent::find()->where(['{{%yes_payment}}.isdel' => 0]);
	}
	
    
	public function itemAlias($list,$item = false,$bykey = false)
	{
		$lists = [
			/* example list of item alias for a field with name field */
			'status'=>[							
							0=>Yii::t('app','disabled'),
							1=>Yii::t('app','enabled'),
							2=>Yii::t('app','closed'),
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
        return $this->hasMany(Confirmation::className(), ['payment_id' => 'id']);
    }
}
