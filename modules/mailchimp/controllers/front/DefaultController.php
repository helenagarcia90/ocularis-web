<?php

/* @var $this->module MailChimpModule */

Yii::import("modules.mailchimp.vendors.mailchimp.Mailchimp");

class DefaultController extends Controller
{
	
	public function actionIndex($list_id = null)
	{
		
		$model = new MailchimpForm();
		
		if($list_id === null)
			$list_id = $this->module->list_id;
		
		
		$model->id = $list_id;
		
		if(isset($_POST['MailchimpForm']))
		{

			$model->attributes = $_POST["MailchimpForm"];

			if($model->id === null)
				throw new CException("You must specify the Mailchimp list id");
			
			if($model->validate())
			{
			
				$mailchimp = new Mailchimp($this->module->apikey);
				
				try {
					$mailchimp->lists->subscribe($model->id,
						array("email" => $model->email),
						$model->merge_vars);
					
					Yii::app()->user->setFlash("newsletterok",Yii::t('MailchimpModule.main',"Thank you, your subscription has been taken into account."));
				}
				catch(Exception $e)
				{
					$model->addError('', $e->getMessage());
				}
				
			}
		
		}
		
		$this->render('index', array(
			'model' => $model,
		));
	}
	
	
	
}