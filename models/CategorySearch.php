<?php

namespace amilna\yes\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use amilna\yes\models\Category;

/**
 * CategorySearch represents the model behind the search form about `amilna\yes\models\Category`.
 */
class CategorySearch extends Category
{

	
	/*public $parentId;*/
	/*public $categoriesId;*/
	/*public $catprosId;*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'isdel'], 'integer'],
            [['title', 'description', 'image'/*, 'parentId', 'categoriesId', 'catprosId'*/], 'safe'],
            [['status'], 'boolean'],
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
        $query = Category::find();
        
                
        $query->joinWith([/*'parent', 'categories', 'catpros'*/]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        /* uncomment to sort by relations table on respective column
		$dataProvider->sort->attributes['parentId'] = [			
			'asc' => ['{{%parent}}.id' => SORT_ASC],
			'desc' => ['{{%parent}}.id' => SORT_DESC],
		];
		$dataProvider->sort->attributes['categoriesId'] = [			
			'asc' => ['{{%categories}}.id' => SORT_ASC],
			'desc' => ['{{%categories}}.id' => SORT_DESC],
		];
		$dataProvider->sort->attributes['catprosId'] = [			
			'asc' => ['{{%catpros}}.id' => SORT_ASC],
			'desc' => ['{{%catpros}}.id' => SORT_DESC],
		];*/

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }				
		
        $query->andFilterWhere([
            'status' => $this->status,
            /*['id','{{%parent}}']
            ['id','{{%categories}}']
            ['id','{{%catpros}}']*/
        ]);

        $params = self::queryNumber([['id'],['parent_id'],['isdel']/*['id','{{%parent}}'],['id','{{%categories}}'],['id','{{%catpros}}']*/]);
		foreach ($params as $p)
		{
			$query->andFilterWhere($p);
		}
        $params = self::queryString([['title'],['description'],['image']/*['id','{{%parent}}'],['id','{{%categories}}'],['id','{{%catpros}}']*/]);
		foreach ($params as $p)
		{
			$query->andFilterWhere($p);
		}
        return $dataProvider;
    }
}
