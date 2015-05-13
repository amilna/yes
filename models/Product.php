<?php

namespace amilna\yes\models;

use Yii;

/**
 * This is the model class for table "{{%yes_product}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $data
 * @property string $tags
 * @property string $images
 * @property integer $author_id
 * @property boolean $isfeatured
 * @property integer $status
 * @property string $time
 * @property integer $isdel
 *
 * @property User $author
 * @property YesSale[] $yesSales
 * @property YesCatPro[] $yesCatPros
 */
class Product extends \yii\db\ActiveRecord
{
    public $dynTableName = '{{%yes_product}}';    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {        
        $mod = new Product();        
        return $mod->dynTableName;
    }
    
    public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			$this->discount = ($this->discount == null?0:$this->discount);
			return true;
		} else {
			return false;
		}
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'sku', 'description', 'content', 'data','status'], 'required'],
            [['content', 'images'], 'string'],
            [['author_id', 'status', 'isdel'], 'integer'],
            [['isfeatured'], 'boolean'],
            [['price','discount'], 'number'],
            [['data', 'tags', 'time'], 'safe'],
            [['title','sku'], 'string', 'max' => 65],
            [['description'], 'string', 'max' => 155],
            //[['tags'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'sku' => Yii::t('app', 'SKU'),
            'description' => Yii::t('app', 'Description'),
            'content' => Yii::t('app', 'Content'),
            'price' => Yii::t('app', 'Price'),
            'discount' => Yii::t('app', 'Discount (%)'),
            'data' => Yii::t('app', 'Data'),
            'tags' => Yii::t('app', 'Tags'),
            'images' => Yii::t('app', 'Images'),
            'author_id' => Yii::t('app', 'Author ID'),
            'isfeatured' => Yii::t('app', 'Promoted'),
            'status' => Yii::t('app', 'Status'),
            'time' => Yii::t('app', 'Time'),
            'isdel' => Yii::t('app', 'Isdel'),
        ];
    }
    
	public function itemAlias($list,$item = false,$bykey = false)
	{
		$lists = [
			/* example list of item alias for a field with name field */
			'status'=>[							
							0=>Yii::t('app','Draft'),							
							1=>Yii::t('app','Available'),
							2=>Yii::t('app','Upcoming'),
							3=>Yii::t('app','Out of Stock'),
							4=>Yii::t('app','On Sale'),
							5=>Yii::t('app','Free'),
						],			
			'isfeatured'=>[							
							0=>Yii::t('app','No'),							
							1=>Yii::t('app','Promoted'),						
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
    public function getAuthor()
    {
        $userClass = Yii::$app->getModule('yes')->userClass;
        return $this->hasOne($userClass::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::className(), ['product_id' => 'id'])->where("isdel=0");
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatPro()
    {
        return $this->hasMany(CatPro::className(), ['product_id' => 'id'])->where("isdel=0");
    }
    
    public function getTags()
	{
		$models = $this->find()->all();
		$tags = [];
		foreach ($models as $m)
		{
			$ts = explode(",",$m->tags);
			foreach ($ts as $t)
			{	
				if (!in_array($t,$tags))
				{
					$tags[$t] = $t;
				}
			}	
		}
		return $tags;
	}
	
	public function getRecent($limit = 5)
	{
		return ProductSearch::find()->orderBy('id desc')->limit($limit)->all();		
	}
	
	public function getArchived($limit = 6)
	{
		$res =  $this->db->createCommand("SELECT 
				substring(concat('',time) from 1 for 7) as month
				FROM ".$this->tableName()." as p
				WHERE isdel = 0
				GROUP BY month				
				ORDER BY month desc
				LIMIT :limit")
				->bindValues(["limit"=>$limit])->queryAll();						
        
        return ($res == null?[]:$res);        
	}
	
	public function toMoney($val,$dec = 2,$sym = true)
    {
		$module = Yii::$app->getModule("yes");
		return ($sym?$module->currency["symbol"]:"").number_format($val,$dec,$module->currency["decimal_separator"],$module->currency["thousand_separator"]);	
	}
}
