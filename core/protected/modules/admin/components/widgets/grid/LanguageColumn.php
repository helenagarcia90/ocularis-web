<?php

class LanguageColumn extends DataColumn
{
	
	public $name = 'lang';	
	
	public function init()
	{
		parent::init();
		$this->visible = count(Yii::app()->languageManager->langs)>1;
		
		if(!isset($this->headerHtmlOptions['class']))
			$this->headerHtmlOptions['class'] = 'col-xs text-center hidden-xs';
		else
			$this->headerHtmlOptions['class'] .= ' col-xs text-center hidden-xs';
		
		if(!isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] = 'col-xs text-center hidden-xs';
		else
			$this->htmlOptions['class'] .= ' col-xs text-center hidden-xs';
		
		if(!isset($this->filterHtmlOptions['class']))
			$this->filterHtmlOptions['class'] = 'col-xs text-center hidden-xs';
		else
			$this->filterHtmlOptions['class'] .= ' col-xs text-center hidden-xs';
	}
	
	protected function renderDataCellContent($row,$data)
	{
		if(!isset($data->language))
			throw new CException("The model does not have a language");
			
		echo AdminHelper::flag($data->language);
	}
	
}