<?php

namespace amilna\yes\controllers;

use Yii;
use amilna\yes\models\Customer;
use amilna\yes\models\CustomerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     * @params string $format, array $arraymap, string $term
     * @return mixed
     */
    public function actionIndex($format= false,$arraymap= false,$term = false)
    {
        $searchModel = new CustomerSearch();        
        $req = Yii::$app->request->queryParams;
        if ($term) { $req[basename(str_replace("\\","/",get_class($searchModel)))]["term"] = $term;}        
        $dataProvider = $searchModel->search($req);				

		if (Yii::$app->request->post('hasEditable')) {			
			$Id = Yii::$app->request->post('editableKey');
			$model = Customer::findOne($Id);
	 
			$out = json_encode(['id'=>$Id,'output'=>'', 'message'=>'','data'=>'null']);	 			
			$post = [];
			$posted = current($_POST['CustomerSearch']);
			$post['Customer'] = $posted;						
						
			if ($model->load($post)) {
				
				$model->last_time = date('Y-m-d H:i:s');
				if ($model->save())
				{
										
				}
	 				
				$output = '';	 	
				if (isset($posted['last_action'])) {				   
				   $output =  $model->itemAlias('last_action',$model->last_action); // new value for edited td
				   $data = json_encode([5=>$model->last_time]); // affected td index with new html at the same row
				} 
					 
				$out = json_encode(['id'=>$model->id,'output'=>$output, "data"=>$data,'message'=>'']);
			} 			
			echo $out;
			return;
		}

		
		$post = Yii::$app->request->post();
		$format = (isset($post["format"])?$post["format"]:$format);
		
        if ($format == 'json')
        {
			$model = [];
			foreach ($dataProvider->getModels() as $d)
			{
				$obj = $d->attributes;
				if ($arraymap)
				{
					$map = explode(",",$arraymap);
					if (count($map) == 1)
					{
						$obj = $d[$arraymap];
					}
					else
					{
						$obj = [];					
						foreach ($map as $a)
						{
							$k = explode(":",$a);						
							$v = (count($k) > 1?$k[1]:$k[0]);
							$obj[$k[0]] = ($v == "Obj"?json_encode($d->attributes):(isset($d->$v)?$d->$v:null));
						}
					}
				}
				
				if ($term)
				{
					if (!in_array($obj,$model))
					{
						array_push($model,$obj);
					}
				}
				else
				{	
					array_push($model,$obj);
				}
			}			
			return \yii\helpers\Json::encode($model);	
		}
		else
		{
			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}	
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @additionalParam string $format
     * @return mixed
     */
    public function actionView($id,$format= false)
    {
        $model = $this->findModel($id);
        
        if ($format == 'json')
        {
			return \yii\helpers\Json::encode($model);	
		}
		else
		{
			return $this->render('view', [
				'model' => $model,
			]);
		}        
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();
		$model->last_time = date("Y-m-d H:i:s");	
		$model->last_action = 0;
		$model->isdel = 0;	
		
		if (Yii::$app->request->post())        
        {
			$post = Yii::$app->request->post();						
			
			$addresses = [];
						
			if (isset($post['Customer']['addresses']))
			{
				$addresses = $post['Customer']['addresses'];
				$post['Customer']['addresses'] = json_encode($addresses);
			}	
			
			$model->load($post);			
			
			if ($model->save()) {															
				return $this->redirect(['view', 'id' => $model->id]);            
			} else {				
				$model->addresses = json_encode($addresses);
			}
		}	
        
        return $this->render('create', [
			'model' => $model,
		]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

		if (Yii::$app->request->post())        
        {
			$post = Yii::$app->request->post();						
			
			$addresses = [];
						
			if (isset($post['Customer']['addresses']))
			{
				$addresses = $post['Customer']['addresses'];
				$post['Customer']['addresses'] = json_encode($addresses);
			}	
			
			$model->load($post);			
			
			if ($model->save()) {															
				return $this->redirect(['view', 'id' => $model->id]);            
			}
		}				
        
        return $this->render('update', [
			'model' => $model,
		]);
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {        
		$model = $this->findModel($id);        
        $model->isdel = 1;
        $model->save();
        //$model->delete(); //this will true delete
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionSearch($format= false,$arraymap= false,$term = false)
    {        		
		$post = Yii::$app->request->post();
		$format = (isset($post["format"])?$post["format"]:$format);
		$term = (isset($post["term"])?$post["term"]:$term);				
		
		$sql = "name=:name";
		$search = [":name"=>$term];
		$arraymap = "name";
		$rs = false;
		if (isset($post["email"]))		
		{
			if (!empty($post["email"]))
			{
				$sql .= " AND email = :email";
				$search[":email"] = $post["email"];				
				$rs = true;
			}			
		}
		if (isset($post["phones"]))		
		{
			if (!empty($post["phones"]))
			{
				$sql .= " AND concat(',',phones,',') LIKE :phones";
				$search[":phones"] = "%,".$post["phones"].",%";				
				$rs = true;
			}			
		}				
		
		if ($rs)
		{
			$arraymap = "name,email,phones,addresses";			
		}				
		
		$models = Customer::find()->where($sql,$search)->all();	
		
		//print_r($search);
		//die($sql);	
        if ($format == 'json')
        {
			$model = [];
			foreach ($models as $d)
			{
				$obj = $d->attributes;
				if ($arraymap)
				{
					$map = explode(",",$arraymap);
					if (count($map) == 1)
					{
						$obj = $d[$arraymap];
					}
					else
					{
						$obj = [];					
						foreach ($map as $a)
						{
							$k = explode(":",$a);						
							$v = (count($k) > 1?$k[1]:$k[0]);
							$obj[$k[0]] = ($v == "Obj"?json_encode($d->attributes):(isset($d->$v)?$d->$v:null));
						}
					}
				}
				
				if ($term)
				{
					if (!in_array($obj,$model))
					{
						array_push($model,$obj);
					}
				}
				else
				{	
					array_push($model,$obj);
				}
			}			
			return \yii\helpers\Json::encode($model);	
		}		
    }    
}
