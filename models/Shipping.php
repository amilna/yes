<?php

namespace amilna\yes\models;

use Yii;

/**
 * This is the model class for table "{{%yes_shipping}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $city
 * @property string $area
 * @property string $data
 * @property integer $status
 * @property integer $isdel
 */
class Shipping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yes_shipping}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'city', 'area', 'data'], 'required'],
            [['code'], 'unique'],
            [['data'], 'string'],
            [['status', 'isdel'], 'integer'],
            [['code', 'city', 'area'], 'string', 'max' => 255]
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
            'city' => Yii::t('app', 'City'),
            'area' => Yii::t('app', 'Area'),
            'data' => Yii::t('app', 'Data'),
            'status' => Yii::t('app', 'Status'),
            'isdel' => Yii::t('app', 'Isdel'),
        ];
    }
    
	public function itemAlias($list,$item = false,$bykey = false)
	{
		$lists = [
			/* example list of item alias for a field with name field */
			'status'=>[							
							0=>Yii::t('app','not available'),							
							1=>Yii::t('app','available'),
							2=>Yii::t('app','COD coverage'),
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
	
	public function getCityArea()
    {
		return $this->city." (".$this->area.")";
	}	
	
	public function toMoney($val,$dec = 2,$sym = true)
    {
		$module = Yii::$app->getModule("yes");
		return ($sym?$module->currency["symbol"]:"").number_format($val,$dec,$module->currency["decimal_separator"],$module->currency["thousand_separator"]);	
	}
		
}
