<?php

class MailchimpForm extends CFormModel
{
	
	
	public $email;
	public $id;
	public $merge_vars = array();
	
	public function rules()
	{
		return array(
			
				array('email, id','required'),
				array('merge_vars', 'safe'),
				
		);
		
		
	}

	public function afterValidate()
	{
		
		
		$mailchimp = new Mailchimp(Yii::app()->controller->module->apikey);
		
		$lists = $mailchimp->lists;
		
		$vars = $lists->mergeVars(array($this->id));
		
		foreach($vars['data'][0]['merge_vars'] as $var)
		{
			if($var['id'] === 0) //Email field
				continue;
			
			if($var['req']===true && (!isset($this->merge_vars[$var['tag']]) || empty($this->merge_vars[$var['tag']]) ))
				$this->addError('merge_vars_'.$var['tag'], Yii::t('yii', '{attribute} cannot be blank.',array('{attribute}' => $var['name'])));
			

		}
		
	}
	
	public function attributeLabels()
	{
		return array(
			
				'email' => Yii::t('MailchimpModule.main', "Email"),
				
		);
	} 

	
	
}