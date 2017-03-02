<?php

Yii::import('zii.widgets.CMenu');

class MenuWidget extends CMenu{

	public $alias = null;
	public $anchor = null;
	
	public function init()
	{

		if($this->alias === null && $this->anchor === null)
			throw new CException("you must specify the menu Alias");
		
		Yii::beginProfile('application.components.widgets.MenuWidget.init','application.components.widgets.MenuWidget');
		
		if($this->getId(false) === null)
		{
			$this->setId('mainmenu');
		}
		
		if($this->anchor === null) //backward support
			$this->anchor = $this->alias;
		
		$lang = Yii::app()->language;
		
		if( ($items = Yii::app()->cache->get("MenuWidget.{$this->anchor}.{$lang}")) === false )
		{
		
			$menu = Menu::model()->with(array('menuItems' => array('scopes' => array('root','active'))))->findByAttributes(array('lang' => $lang, 'anchor' => $this->anchor));
	
	
			if($menu != null)
			{
				$this->items = $this->generateMenuRecursive($menu->menuItems(array('scopes' => array('root','active'))));
			}			
			
			
			Yii::app()->cache->set("MenuWidget.{$this->anchor}.{$lang}", $this->items);
			
		}
		else
			$this->items = $items;
		
		Yii::endProfile('application.components.widgets.MenuWidget.init','application.components.widgets.MenuWidget');
		
		parent::init();
	}
	
	private function generateMenuRecursive($items)
	{
		
		$allItems = array();
		
		foreach($items as $item)
		{
			/* @var $item MenuItem */
			$menuItem = array();
			$menuItem['label'] = $item->label;
						
			if( ($url = Helper::parseLink($item->link)) !== false)
				$menuItem['url'] = $url;
			
			
			if($item->target != null)
				$menuItem['linkOptions']['target'] = $item->target;
				
			if(count($item->items(array('scopes' => 'active'))) > 0)
			{
				$menuItem['items'] = $this->generateMenuRecursive($item->items(array('scopes' => 'active')));
			}
			
			$allItems[]  = $menuItem;
		}
		
		return $allItems;
	} 
		
}