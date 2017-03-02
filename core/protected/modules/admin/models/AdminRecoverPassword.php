<?php

class AdminRecoverPassword extends CFormModel
{
	
	public $email;
	
	public function rules()
	{
		return array(
			
				array('email' , 'required')
				
		);
	}
	
}