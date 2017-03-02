<?php


class PageSelectorValidator extends CValidator
{
	
	public $allowEmpty = false;
	
	
	protected function validateAttribute($object,$attribute)
	{
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;
		
		if( substr($value,0,4) === 'url:')
		{
			if(strlen($value) === 4)
			{
				$object->addError($attribute, Yii::t('yii', "{attribute} cannot be blank.", array("{attribute}" => Yii::t('pageSelector', 'Url'))));
			}
			
		}
		elseif( ($pos = strpos($value, "?")) === false)
		{
			return;
		}
		else
		{
			$route = substr($value, 0, $pos);
			parse_str(substr($value, $pos+1), $params);
			
			foreach(PageSelector::getItemTypesConfig() as $alias => $config)
			{
			
				foreach($config as $index => $item)
				{
			
					if($index === 0)
						continue;
			
					if($route === $item['route'] && isset($item['params']))
					{
						foreach($item['params'] as $id => $param)
						{
							if( (!isset($param['required']) || $param['required']) && (!isset($params[$id]) || $params[$id] == '') )
								$object->addError('params_'.$id, Yii::t('yii', "{attribute} cannot be blank.",array('{attribute}' => $param['label'])));
			
						}
					}
				}
			}
			
		}
				
		
	}
	
	
}