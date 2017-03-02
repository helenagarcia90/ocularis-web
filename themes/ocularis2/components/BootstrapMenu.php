<?php

class BootstrapMenu extends MenuWidget
{
	
	public function init()
	{
		parent::init();
		
		if(isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] .= 'nav navbar-nav';
		else
			$this->htmlOptions['class'] = 'nav navbar-nav';
		
		$this->submenuHtmlOptions = array('role' => 'menu', 'class' => 'dropdown-menu');
		
		foreach($this->items as &$item)
		{
			if(isset ($item['items']) && count($item['items']) >0 )
			{
				
				if( !isset($item['url']))
					$item['url'] = '#';
				
				$item['label'] .= ' <span class="caret"></span>';
				
				$class = 'dropdown-toggle';

				$item['linkOptions']['data-toggle'] = 'dropdown';
				
				if(isset($item['linkOptions']['class']))
					$item['linkOptions']['class'] .= ' ' . $class;
				else
					$item['linkOptions']['class'] = $class;
			}
		}
		
	}
	
}