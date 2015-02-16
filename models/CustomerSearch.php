<?php

namespace amilna\yes\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use amilna\yes\models\Customer;

/**
 * CustomerSearch represents the model behind the search form about `amilna\yes\models\Customer`.
 */
class CustomerSearch extends Customer
{

	public $search;
	/*public $yesordersId;*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'last_action', 'isdel'], 'integer'],
            [['name', 'phones', 'addresses', 'email', 'search', 'last_time'/*, 'yesordersId'*/], 'safe'],
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
        $query = Customer::find();
        
                
        $query->joinWith([/*'yesorders'*/]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        /* uncomment to sort by relations table on respective column
		$dataProvider->sort->attributes['yesordersId'] = [			
			'asc' => ['{{%yesorders}}.id' => SORT_ASC],
			'desc' => ['{{%yesorders}}.id' => SORT_DESC],
		];*/

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }				
		
        $params = self::queryNumber([['id'],['last_action'],['isdel']/*['id','{{%yesorders}}']*/]);
		foreach ($params as $p)
		{
			$query->andFilterWhere($p);
		}
        $params = self::queryString([['name'],['phones'],['addresses'],['email']/*['id','{{%yesorders}}']*/]);
		foreach ($params as $p)
		{
			$query->andFilterWhere($p);
		}
        $params = self::queryTime([['last_time']/*['id','{{%yesorders}}']*/]);
		foreach ($params as $p)
		{
			$query->andFilterWhere($p);
		}		
		/* example to use search all in field1,field2,field3 or field4 */
		if ($this->search)
		{
			$query->andFilterWhere(["OR","lower(name) like '%".strtolower($this->search)."%'",
				["OR","lower(email) like '%".strtolower($this->search)."%'",
					["OR","lower(phones) like '%".strtolower($this->search)."%'",
						"lower(addresses) like '%".strtolower($this->search)."%'"						
					]
				]
			]);	
		}	
		

        return $dataProvider;
    }
}
