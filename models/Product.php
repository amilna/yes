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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yes_product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'content', 'data'], 'required'],
            [['content', 'data', 'images'], 'string'],
            [['author_id', 'status', 'isdel'], 'integer'],
            [['isfeatured'], 'boolean'],
            [['time'], 'safe'],
            [['title'], 'string', 'max' => 65],
            [['description'], 'string', 'max' => 155],
            [['tags'], 'string', 'max' => 255]
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
            'description' => Yii::t('app', 'Description'),
            'content' => Yii::t('app', 'Content'),
            'data' => Yii::t('app', 'Data'),
            'tags' => Yii::t('app', 'Tags'),
            'images' => Yii::t('app', 'Images'),
            'author_id' => Yii::t('app', 'Author ID'),
            'isfeatured' => Yii::t('app', 'Isfeatured'),
            'status' => Yii::t('app', 'Status'),
            'time' => Yii::t('app', 'Time'),
            'isdel' => Yii::t('app', 'Isdel'),
        ];
    }
	
	/* uncomment to undisplay deleted records (assumed the table has column isdel)
	public static function find()
	{
		return parent::find()->where(['{{%yes_product}}.isdel' => 0]);
	}
	*/
    
	public function itemAlias($list,$item = false,$bykey = false)
	{
		$lists = [
			/* example list of item alias for a field with name field
			'afield'=>[							
							0=>Yii::t('app','an alias of 0'),							
							1=>Yii::t('app','an alias of 1'),														
						],			
			*/			
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
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYesSales()
    {
        return $this->hasMany(YesSale::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYesCatPros()
    {
        return $this->hasMany(YesCatPro::className(), ['product_id' => 'id']);
    }
}
