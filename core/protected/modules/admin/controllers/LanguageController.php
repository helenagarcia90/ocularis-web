<?php

class LanguageController extends AdminController
{
	
	public function actionIndex()
	{
		
		if(!Yii::app()->user->checkAccess('viewLanguage'))
			throw new CHttpException(403);
		
		$this->breadcrumbs[] = Yii::t('lang', 'Languages');
		
		$action = isset($_POST['action']) ? $_POST['action'] : '';
		
		switch($action)
		{
			case 'delete-lang':
				$this->deleteLangs();
				break;
		}
		
		

		$model=new Lang('search');
		$model->unsetAttributes();  
		
		
		if(isset($_GET['Lang']))
			$model->attributes=$_GET['Lang'];
		
		$this->render('index',array(
				'model' => $model,
		));
	
	}

	public function actionAjaxChangeDefault($lang, $value)
	{
		
		if(!Yii::app()->user->checkAccess('updateLanguage'))
			throw new CHttpException(403);
		
		$transaction = Lang::model()->getDbConnection()->beginTransaction();
		
		//set default to given and make sure it is active + remove default to current one and set default
		//make usre both are correctly updated and that only 1 row is affected (maintains data consistency)
		if(Lang::model()->updateAll(array('default' => 0),"`default` = 1") === 1 && Lang::model()->updateByPk($lang, array('default' => 1),"active = 1") === 1)
			$transaction->commit();
		else
			$transaction->rollBack();
		
		Yii::app()->end();
	}
	
	public function actionAjaxChangeActive($lang,$value)
	{
		
		if(!Yii::app()->user->checkAccess('updateLanguage'))
			throw new CHttpException(403);
		
		Lang::model()->updateByPk($lang, array('active' => $value == 'true'),"`default` = 0");
		Yii::app()->cache->delete(CUrlManager::CACHE_KEY);
		Yii::app()->end();
	}

}