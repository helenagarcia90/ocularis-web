<?php

class AdminUserIdentity extends CUserIdentity
{
	
	public function authenticate()
	{

		$admin = Admin::model()->findByAttributes(array('email' => $this->username));
		
		if($admin===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($admin->password!==md5($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->errorCode=self::ERROR_NONE;
			$this->setState('id_admin', $admin->id_admin);
			$this->setState('email', $admin->email);
			$this->setState('name', $admin->name);
			$this->setState('lang', $admin->lang);
			$this->setState('editor', $admin->editor);
		}		
		return !$this->errorCode;
	}
}