<?php

namespace amilna\yes\controllers;

use Yii;
use amilna\yes\models\Shipping;
use amilna\yes\models\ShippingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShippingController implements the CRUD actions for Shipping model.
 */
class ShippingController extends Controller
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
     * Lists all Shipping models.
     * @params string $format, array $arraymap, string $term
     * @return mixed
     */
    public function actionIndex($format= false,$arraymap= false,$term = false)
    {
        $searchModel = new ShippingSearch();                
        $req = Yii::$app->request->queryParams;
        if ($term) { $req[basename(str_replace("\\","/",get_class($searchModel)))]["term"] = $term;}        
        $dataProvider = $searchModel->search($req);				
		
		$post = Yii::$app->request->post();
		$format = (isset($post["format"])?$post["format"]:$format);
		$term = (isset($post["term"])?$post["term"]:$term);
		
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
						$obj = (isset($d[$arraymap])?$d[$arraymap]:null);						
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
     * Lists all Shipping models.
     * @params string $format, array $arraymap, string $term
     * @return mixed
     */
    public function actionAdmin($format= false,$arraymap= false,$term = false)
    {
        $searchModel = new ShippingSearch();                
        $req = Yii::$app->request->queryParams;
        if ($term) { $req[basename(str_replace("\\","/",get_class($searchModel)))]["term"] = $term;}        
        $dataProvider = $searchModel->search($req);				
		
		$post = Yii::$app->request->post();
		$format = (isset($post["format"])?$post["format"]:$format);
		$term = (isset($post["term"])?$post["term"]:$term);
		
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
						$obj = (isset($d[$arraymap])?$d[$arraymap]:null);						
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
			$res = ["status"=>false,"count"=>0,"n"=>0];
			$post = Yii::$app->request->post();
			$session = Yii::$app->session;
			if ($post)        
			{				
				$n = isset($post['Shipping']['n'])?$post['Shipping']['n']:false;				
							
				$data = [];	
				if (isset($session["yes-shipping-f"]))				
				{																																			
					$data = $session["yes-shipping-f"];										
					$res = \amilna\yes\components\Helpers::importCsv(false,$data,$n);
				}
				elseif ($n == -1)
				{
					$session["yes-shipping-dn"] = 0;
					unset($session["yes-shipping-dn"]);
					$res = ["status"=>true,"count"=>1,"n"=>0];
				}		
				else
				{																			
					$session["tes"] = "tos";
					if (isset($post['Shipping']['csv']))
					{
						$file = $post['Shipping']['csv'];												
						
						if (strpos($file,"http") !== false)
						{				
							$curl = curl_init();					
							if ($curl)
							{				
								
								$f = fopen('php://temp', 'w+');					
								curl_setopt($curl, CURLOPT_URL, $file);
								curl_setopt($curl, CURLOPT_FILE, $f);					
								curl_exec($curl);
								curl_close($curl);

								rewind($f);															
								$res = \amilna\yes\components\Helpers::importCsv($f);
								fclose($f);
				
							}
						}
						elseif (strpos($file,"http") === false)
						{					
							
							$module = Yii::$app->getModule("yes");
							$path = \Yii::getAlias($module->uploadDir);
							$f = $path."/files/".$file;																			
							
							if(file_exists($f) && is_readable($f))
							{						
								
								$f = fopen($f,"r");
								$res = \amilna\yes\components\Helpers::importCsv($f);						
								fclose($f);
							}
						}
					}
				}				
								
				return \yii\helpers\Json::encode($res);	
			}
			else
			{				
				unset($session["yes-shipping-f"]);
				unset($session["yes-shipping-dn"]);	
			}
			
			
			
			return $this->render('admin', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		}	
    }

    /**
     * Displays a single Shipping model.
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
     * Creates a new Shipping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Shipping();
		$model->isdel = 0;
		
		if (Yii::$app->request->post())        
        {
			$post = Yii::$app->request->post();									
			$data = [];						
			if (isset($post['Shipping']['data']))
			{
				$data = $post['Shipping']['data'];
				$post['Shipping']['data'] = json_encode($data);
			}				
			$model->load($post);			
			
			if ($model->save()) {																
				//return $this->redirect(['view', 'id' => $model->id]);            
				return $this->redirect(['admin']);            
			} else {				
				$model->data = json_encode($data);
			}
		}	
        
		return $this->render('create', [
			'model' => $model,
		]);
        
    }

    /**
     * Updates an existing Shipping model.
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
			$data = [];						
			if (isset($post['Shipping']['data']))
			{
				$data = $post['Shipping']['data'];
				$post['Shipping']['data'] = json_encode($data);
			}				
			$model->load($post);			
			
			if ($model->save()) {																
				//return $this->redirect(['view', 'id' => $model->id]);            			
				return $this->redirect(['admin']);            
			}
		}					
        
		return $this->render('update', [
			'model' => $model,
		]);
    }

    /**
     * Deletes an existing Shipping model.
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
     * Finds the Shipping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Shipping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Shipping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
     /**
     * Import data from csv (example of csv available inside migrations folder).
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionImport($n = false)
    {        	
		$file = "";
		$session = Yii::$app->session;
		if (Yii::$app->request->post() || $n)        
        {
			$post = Yii::$app->request->post();									
						
			$data = [];	
			if (!isset($session["yes-shipping-f"]))
			{																				
				if (isset($post['Shipping']['csv']))
				{
					$file = $post['Shipping']['csv'];												
					
					if (strpos($file,"http") !== false)
					{				
						$curl = curl_init();					
						if ($curl)
						{				
							
							$f = fopen('php://temp', 'w+');					
							curl_setopt($curl, CURLOPT_URL, $file);
							curl_setopt($curl, CURLOPT_FILE, $f);					
							curl_exec($curl);
							curl_close($curl);

							rewind($f);
														
							$res = \amilna\yes\components\Helpers::importCsv($f);
							print_r($res);

							fclose($f);
			
						}
					}
					elseif (strpos($file,"http") === false)
					{					
						
						$module = Yii::$app->getModule("yes");
						$path = \Yii::getAlias($module->uploadDir);
						$f = $path.$file;
						
						$f = str_replace(["../backend/web/static/yii2/advanced/backend/","%20"],[""," "],$f);
						//					
						
						if(file_exists($f) && is_readable($f))
						{						
							$f = fopen($f,"r");
							$res = \amilna\yes\components\Helpers::importCsv($f);						
							fclose($f);
						}
					}
				}
			}
			else
			{				
				$data = $session["yes-shipping-f"];				
				$res = \amilna\yes\components\Helpers::importCsv(false,$data,$n);
			}			
		}	
        
		return $this->render('import', [
			'file' => $file,
		]);
        
    }
}
