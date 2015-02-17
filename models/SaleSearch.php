<?php

namespace amilna\yes\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use amilna\yes\models\Sale;

/**
 * SaleSearch represents the model behind the search form about `amilna\yes\models\Sale`.
 */
class SaleSearch extends Sale
{

	
	public $productTitle;
	public $orderReference;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'order_id', 'isdel'], 'integer'],
            [['data', 'amount', 'orderReference','productTitle','quantity', 'time'/*, 'productId', 'orderId'*/], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

	private function queryString($fields)
	{		
		$params = [];
		foreach ($fields as $afield)
		{
			$field = $afield[0];
			$tab = isset($afield[1])?$afield[1]:false;			
			if (!empty($this->$field))
			{				
				array_push($params,["like", "lower(".($tab?$tab.".":"").$field.")", strtolower($this->$field)]);
			}
		}	
		return $params;
	}	
	
	private function queryNumber($fields)
	{		
		$params = [];
		foreach ($fields as $afield)
		{
			$field = $afield[0];
			$tab = isset($afield[1])?$afield[1]:false;			
			if (!empty($this->$field))
			{				
				$number = explode(" ",$this->$field);			
				if (count($number) == 2)
				{									
					array_push($params,[$number[0], ($tab?$tab.".":"").$field, $number[1]]);	
				}
				elseif (count($number) > 2)
				{															
					array_push($params,[">=", ($tab?$tab.".":"").$field, $number[0]]);
					array_push($params,["<=", ($tab?$tab.".":"").$field, $number[0]]);
				}
				else
				{					
					array_push($params,["=", ($tab?$tab.".":"").$field, str_replace(["<",">","="],"",$number[0])]);
				}									
			}
		}	
		return $params;
	}
	
	private function queryTime($fields)
	{		
		$params = [];
		foreach ($fields as $afield)
		{
			$field = $afield[0];
			$tab = isset($afield[1])?$afield[1]:false;			
			if (!empty($this->$field))
			{				
				$time = explode(" - ",$this->$field);			
				if (count($time) > 1)
				{								
					array_push($params,[">=", "concat('',".($tab?$tab.".":"").$field.")", $time[0]]);	
					array_push($params,["<=", "concat('',".($tab?$tab.".":"").$field.")", $time[1]." 24:00:00"]);
				}
				else
				{
					if (substr($time[0],0,2) == "< " || substr($time[0],0,2) == "> " || substr($time[0],0,2) == "<=" || substr($time[0],0,2) == ">=") 
					{					
						array_push($params,[str_replace(" ","",substr($time[0],0,2)), "concat('',".($tab?$tab.".":"").$field.")", trim(substr($time[0],2))]);
					}
					else
					{					
						array_push($params,["like", "concat('',".($tab?$tab.".":"").$field.")", $time[0]]);
					}
				}	
			}
		}	
		return $params;
	}

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Sale::find();
        
                
        $query->joinWith(['product', 'order']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        /* uncomment to sort by relations table on respective column */
		$dataProvider->sort->attributes['productTitle'] = [			
			'asc' => ['{{%yes_product}}.id' => SORT_ASC],
			'desc' => ['{{%yes_product}}.id' => SORT_DESC],
		];
		$dataProvider->sort->attributes['orderReference'] = [			
			'asc' => ['{{%yes_order}}.id' => SORT_ASC],
			'desc' => ['{{%yes_order}}.id' => SORT_DESC],
		];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }				
		
        $params = self::queryNumber([['id'],['product_id'],['order_id'],['amount'],['quantity'],['isdel']/*['id','{{%product}}'],['id','{{%order}}']*/]);
		foreach ($params as $p)
		{
			$query->andFilterWhere($p);
		}
        $params = self::queryString([['data']/*['id','{{%product}}'],['id','{{%order}}']*/]);
		foreach ($params as $p)
		{
			$query->andFilterWhere($p);
		}
        $params = self::queryTime([['time']/*['id','{{%product}}'],['id','{{%order}}']*/]);
		foreach ($params as $p)
		{
			$query->andFilterWhere($p);
		}		
		/* example to use search all in field1,field2,field3 or field4
		if ($this->search)
		{
			$query->andFilterWhere(["OR","lower(field1) like '%".strtolower($this->search)."%'",
				["OR","lower(field2) like '%".strtolower($this->search)."%'",
					["OR","lower(field3) like '%".strtolower($this->search)."%'",
						"lower(field4) like '%".strtolower($this->search)."%'"						
					]
				]
			]);	
		}	
		*/
		
		$query->andFilterWhere(["like", "lower({{%yes_product}}.title)", strtolower($this->productTitle)]);
		$query->andFilterWhere(["like", "lower({{%yes_order}}.reference)", strtolower($this->orderReference)]);

        return $dataProvider;
    }
}
