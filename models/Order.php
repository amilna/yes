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
    public $captcha;
    public $captchaRequired = true;
    
    public $dynTableName = '{{%yes_order}}';    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {        
        $mod = new Order();        
        return $mod->dynTableName;
    }
			
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [array_merge(['customer_id', 'reference', 'data', 'log'],($this->captchaRequired?['captcha']:[])), 'required'],
            [['status', 'isdel'], 'integer'],
            [['total'], 'number'],
            [['data', 'log'], 'string'],
            [['time', 'complete_time'], 'safe'],
            [['reference', 'complete_reference'], 'string', 'max' => 255],
            [['captcha'], 'captcha','captchaAction'=>'yes/order/captcha','skipOnEmpty'=>!$this->captchaRequired]
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
        return $this->hasMany(Confirmation::className(), ['order_id' => 'id'])->where("isdel=0");
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
        return $this->hasMany(Sale::className(), ['order_id' => 'id'])->where("isdel=0");
    }
    
    public function toMoney($val,$dec = 2,$sym = true)
    {
		$module = Yii::$app->getModule("yes");
		return ($sym?$module->currency["symbol"]:"").number_format($val,$dec,$module->currency["decimal_separator"],$module->currency["thousand_separator"]);	
	}
	
	public function toHex($string)
	{		
		$hex = '';
		for ($i=0; $i<strlen($string); $i++){
			$ord = ord($string[$i]);
			$hexCode = dechex($ord);
			$hex .= '%'.substr('0'.$hexCode, -2);
		}
		return $hex;
	}
	
	public function createSales()
	{
		$data = json_decode($this->data);		
		$cart = isset($data->cart)?json_decode($data->cart):null;				
		$res = true;
		
		foreach ($cart as $p)
		{																				
			$remarks = "";
			foreach ($p as $k=>$v)
			{
				if (substr($k,0,5) == "data_")
				{
					$remarks .= ($remarks == ""?"":", ").substr($k,5).": ".$v;
				}
			}
			
			$sale = Sale::findOne(['product_id'=>$p->id,'order_id'=>$this->id,'data'=>$remarks]);
			if (!$sale)
			{
				$sale = new Sale();									
			}						
			$sale->time = date("Y-m-d H:i:s");
			$sale->isdel = 0;
			$sale->order_id = $this->id;
			$sale->product_id = $p->id;
			$sale->quantity = $p->quantity;
			$sale->amount = $p->quantity*$p->price;
			$sale->data = $remarks;
			if (!$sale->save())
			{				
				$res = false;
			}
			else
			{
				$customer = Customer::findOne($this->customer_id);
				if ($customer)
				{
					$customer->last_action = 2;
					$customer->last_time = date("Y-m-d H:i:s");
					if (!$customer->save())
					{
						$res = false;
					}	
				}		
			}
		}
		
		return $res;
	}
	
	public function deleteSales()
	{		
		$res = true;
		$sales = Sale::findAll(['order_id'=>$this->id]);
		foreach ($sales as $sale)
		{
			$sale->isdel = 1;
			if (!$sale->save())
			{
				$res = false;
			}
		}
		return $res;
		//return $sale = Sale::deleteAll(['order_id'=>$this->id]);				
	}
}
