<?php


class UserIdentity extends CUserIdentity
{
	
	public function authenticate()
	{

		$user = User::model()->findByAttributes(array('username' => $this->username));
		
		if($user === null)
		{
			//try by email
			$user = User::model()->findByAttributes(array('email' => $this->username));
		}
		
		if($user === null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif(md5($this->password) !== $user->password )
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->errorCode=self::ERROR_NONE;
		
			$this->setState('id_user', $user->id_user);
			$this->setState('username', $user->username);
			$this->setState('email', $user->email);
			
		}
		
		return !$this->errorCode;
	}
}