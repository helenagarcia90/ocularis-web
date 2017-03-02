<?php

class MailchimpModule extends WebModule
{
	
	public $apikey = null;
	public $list_id = null;
	
	public function init()
	{

		$this->setImport(array(
			'mailchimp.models.*'
		));
	
	}

}
