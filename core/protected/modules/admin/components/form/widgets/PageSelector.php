<?php

class PageSelector extends InputField
{
	
	private $_route;
	private $_type;
	private $_params;
	
	public $scope = null; 
	
	public function init()
	{
		$this->labelHtmlOptions['for'] = false;
		parent::init();
	}
	
	public function renderInput()
	{
		
		
			list($name, $fieldId) = $this->resolveNameID();
			
			
			echo CHtml::openTag('div', array('id' => $fieldId.'_container', 'class' => 'form-group'));
			$this->widget('DropDown' , array(	'name' => $fieldId.'_item_type',
												'value' => $this->type,
												'data' => $this->getItemTypesListData(),
												'options' => array('prompt' =>  Yii::t('pageSelector', '- Select type -')),));
												//'hasErrors' => $this->hasModel() ? $this->model->hasErrors($this->attribute) :'' ));
			ECHO CHtml::closeTag('div');
			
			if($this->hasModel())
			{
				echo CHtml::activeHiddenField($this->model, $this->attribute);
			}
			else
			{
				echo CHtml::activeHiddenField($this->name, $this->value);
			}
		
			$typesOptions = array('class' => 'type');
			
			echo CHtml::openTag('div',array('id' => $fieldId.'_item_types'));
			
			$value = $this->hasModel() ? CHtml::value($this->model, $this->attribute) : $this->value;

			echo CHtml::openTag('div', array('id' => $fieldId.'core_custom', 'style' => ($this->type !== 'core_custom' ? 'display: none;' : "") ));
				echo FormHelper::renderFormElement(array('type' => 'text', 'label' => Yii::t('pageSelector', 'Route')), $fieldId.'custom_val', $value);
			echo CHtml::closeTag('div');
			
			if(substr($value, 0,4) === 'url:')
			{
				$url = substr($this->route, 4);
			}
			else
				$url = '';

			echo CHtml::openTag('div', array('id' => $fieldId.'core_url', 'style' => ($this->type !== 'core_url' ? 'display: none;' : "") ));
				echo FormHelper::renderFormElement(array('type' => 'text', 'label' => Yii::t('pageSelector', 'Url')), $fieldId.'url_val', $url);
			echo CHtml::closeTag('div');
			
			foreach(self::getItemTypesConfig() as $alias => $config)
			{
					
					
				foreach($config as $index => $item)
				{
			
					if($index === 0)
						continue;
			
			
			
					$js = "";
					$jsparams = array();
			
			
					$item_id = $alias."_".$item['id'];
					$item_dom_id = $fieldId . $item_id;
									
					if(isset($item['params']))
					{		

						echo CHtml::openTag('div', array('id' => $item_dom_id, 'style' => ($this->type !== $item_id ? 'display: none;' : "") ));
											
						foreach($item['params'] as $id => $param)
						{
			
							$block_id = $item_dom_id."_".$id;

							$js .= "$('body').on('change','#{$block_id}',function(){item_{$item_dom_id}_execute();});";
							$jsparams[] = "if($('#{$block_id}').val()!='')params.push('{$id}='+$('#{$block_id}').val());";
																					

							$value = (isset($this->params[$id]) ? $this->params[$id] : '');
							$name = $block_id;
							
							echo FormHelper::renderFormElement($param, $name, $value);
							
						}

						echo CHtml::closeTag('div');
											
					}
			
					if(count($jsparams))
					{

						$jsparams =  count($jsparams) > 0 ? implode("\n", $jsparams) : "";
						
						$js .= "
								item_{$item_dom_id}_execute = function()
								{
	
									var params = [];
									
									{$jsparams}
									
									var query = '';
									if(params.length>0)
									{
										query = '?' + params.join('&');
									}
									$('#".$fieldId."').val('{$item['route']}'+query);
											
								}";

					}
					else
					{
						
						$js .= "
						
						item_{$item_dom_id}_execute = function()
						{
							$('#".$fieldId."').val('{$item['route']}');
							
						}";
						
					}	
							
					Yii::app()->clientScript->registerScript($item_dom_id,$js);
					
					
				}		
							
			}
					
			
			echo CHtml::closeTag('div');
			$this->registerScripts();
		
	}
	
	public function registerScripts()
	{
		
		list($name, $id) = $this->resolveNameID();
		Yii::app()->clientScript->registerScript($id . '_scripts', '
				
			$("body").on("change","#'.$id.'_item_type",function(){

				$("#'.$id.'_item_types > div").hide();

				if($(this).val() == \'\')
				{
						
					$("#'.$id.'").val("");
					return;			
				}

				$("#'.$id.'_item_types #'.$id .'"+$(this).val()).show();

				
					var fn = window["item_'.$id.'"+$(this).val()+"_execute"];
								
					if(typeof fn === "function") {
					    fn();
					}
							
				

			});
				
				
				item_'.$id.'core_custom_execute = function()
				{
					$("#'.$id.'").val($("#'.$id.'custom_val").val());
				}
		
				$("body").on("change","#'.$id.'custom_val",function(){item_'.$id.'core_custom_execute();});
							
							
				item_'.$id.'core_url_execute = function()
				{
					$("#'.$id.'").val("url:"+$("#'.$id.'url_val").val());
				}
		
				$("body").on("change","#'.$id.'url_val",function(){item_'.$id.'core_url_execute();});
				
				
				
				');
	}
	
	public function getRoute()
	{
				
		if($this->_route !== null)
			return $this->_route;
		
		$route = $this->hasModel() ? CHtml::value($this->model, $this->attribute) : $this->value;
		$this->_route = $route !== null ? $route : '';
		
		if( ($pos = strpos($this->_route, "?")) !== false)
		{
			$this->_route = substr($this->_route, 0, $pos);
		}
		
		
		
		return $this->_route;
	}
	
	public function getParams()
	{
		
		if($this->_params !== null)
			return $this->_params;
		
		$this->_params = array();
		
		$route = $this->hasModel() ? CHtml::value($this->model, $this->attribute) : $this->value;
		$route = $route !== null ? $route : '';
			
		if( ($pos = strpos($route, "?")) !== false)
		{
			$params = substr($route, $pos+1);
			parse_str($params,$this->_params);
		}
		
		return $this->_params;
	}
	
	public function getType()
	{
	
		if($this->_type !== null)
			return $this->_type;
			
		if(substr($this->route,0,4) === 'url:')
			return $this->_type = 'core_url';
		else
		{
	
			foreach(self::getItemTypesConfig() as $group => $config)
			{
					
				foreach($config as $index => $item)
				{
					if($index === 0)
						continue;
						
					if($this->route === $item['route'])
					{
						if(!isset($item['params']) || count(array_intersect_key($this->params, $item['params'])) === count($this->params))
							return $this->_type = $group."_".$item['id'];
					}
				}
			}
				
		}
	
		if($this->route != "")
			return $this->_type = "core_custom";
	
		return $this->_type = '';
		
	}
	
	public function getItemTypesListData()
	{
	
		$types = array();
	
	
		foreach(self::getItemTypesConfig() as $group => $config)
		{
				
			foreach($config as $index => $item)
			{
				if($index === 0)
					continue;
				
				if( isset($item['scope']) && $this->scope !== $item['scope'])
					continue;
					
				$types[$config[0]][$group."_".$item['id']] = $item['label'];
			}
		}
	
	
		$types['Core'] = array_merge($types['Core'], array(
				'core_custom' => Yii::t('pageSelector', 'Custom'),
				'core_url' => Yii::t('pageSelector', 'Url'),
				)
			);
	
		return $types;
	}
	
	
	public static function getItemTypesConfig()
	{
	
		$config = array( 'core'  => array(
	
				Yii::t('app' ,'Core'),
	
				array(
						'route' =>  '#',
						'id' => 'container',
						'label' => Yii::t('pageSelector', 'Container'),
						'scope' => 'menuitem',
				),
				
				array(
						'route' =>  '/site/index',
						'id' => 'home',
						'label' => Yii::t('pageSelector', 'Home Page'),
				),
	
				array(
						'route' =>  '/site/contact',
						'id' => 'contact',
						'label' => Yii::t('pageSelector', 'Contact Page'),
				),
	
				array(
						'route' =>  '/site/page',
						'id' => 'static',
						'label' => Yii::t('pageSelector', 'Static Page'),
						'params' => array(
								'view' => array(
										'label' => Yii::t('pageSelector',"Page name"),
										'type' => 'text',
								)
						)
				),
	
				array(
						'route' =>  '/page/index',
						'id' => 'cms',
						'label' => Yii::t('pageSelector', 'CMS Page'),
	
						'params' => array('id' => array(
								'type' => 'selector',
								'label' => Yii::t('pageSelector',"Page"),
								'url' => Yii::app()->createUrl('/admin/page/selector'),
								'valueModel' => 'Page',
								'valueAttribute' => 'title',
						)
						)
				),
	
				array(
						'route' =>  '/blog/index',
						'id' => 'blog',
						'label' =>Yii::t('pageSelector', 'Blog'),
						'params' => array('id' => array(
								'label' => Yii::t('pageSelector',"Blog"),
								'type' => 'dropdown',
								'prompt' => Yii::t('pageSelector',"- Select Blog -"),
								'items' => 'Blog::getListData()',
						)
						)
				),
					
		)
		);
	
		foreach(Yii::app()->moduleManager->modulesConfig as $id => $module)
		{
	
			if(!isset($module->menuItemTypes))
				continue;
				
			$config[$module->alias] = array($module->name);
			$config[$module->alias] = array_merge($config[$module->alias], $module->menuItemTypes);
				
		}
	
		return $config;
	}
	
	
	
}