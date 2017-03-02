<?php

class PagerUrlRule extends PatternUrlRule
{

	public $template = 'page<page>';

	public $route = '/site/index';
	
	public $references = array(

			'page' => '\d',
	);
	
	public function parseTokens($values)
	{
				if(isset($values['page']))
				{
					$_GET['page'] = $values['page'];
					return true;
				}
				
		
		return false;
	}
	
	
	public function getTokenValues(&$params)
	{
		
			
			if (isset($params['page']))
			{
			
					if(isset($this->tokens['page']))
					{
						$values['page'] = $params['page'];
					}
					
					return $values;
			
			}
			
			return false;
		
		
	}
	
}