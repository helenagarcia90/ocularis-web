<?php

class DefaultController extends AdminController
{
	
	
	public function actionIndex()
	{
		
		$model = new Socio('search');
		
		if(isset($_GET['']))
		
		$this->render('index', array('model' => $model));
		
	}
	
}