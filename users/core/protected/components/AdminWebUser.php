<?php

class AdminWebUser extends CWebUser
{
	public function checkAccess($operation,$params=array(),$allowCaching=true)
	{
		return parent::checkAccess('Superadmin', $params, $allowCaching) || parent::checkAccess($operation, $params, $allowCaching); 
	}
	
	
}