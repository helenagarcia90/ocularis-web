<?php

class BooleanColumn extends DataColumn
{
	
	public $url;
	public $evalDisabled;	 
	
	public $htmlOptions = array (
			'class' => 'text-center',
			'style' => 'width: 100px',
	);
	
		
	public function init()
	{
		parent::init();

		if(!isset($this->headerHtmlOptions['class']))
			$this->headerHtmlOptions['class'] = 'text-center';
		else
			$this->headerHtmlOptions['class'] .= ' text-center';
	}
		
	public function renderFilterCellContent()
	{
		echo $this->grid->blankDisplay;
	}
	
	public function renderDataCellContent($row, $data)
	{
		if($data->{$this->attribute} == 1)
		{
			$html = array('class' => 'glyphicon glyphicon-star', 'style' => 'color:#febf01;');
		}
		else
		{
			$html = array('class' => 'glyphicon glyphicon-star-empty');
		}
		
		$ajax = array(
			'url' => $this->evaluateExpression($this->url, array('data' => $data)),
			'data' => "js:'value='+($(this).attr('data-value')==0)",
			'success' => "js:function(){ $('#{$this->grid->id}').modelGridView('reload'); }",
			'beforeSend' => "js:function(){ $('#{$this->grid->id}').modelGridView('showLoad') }"
		);
				
		if(isset($this->evalDisabled))
			$disabled = $this->evaluateExpression($this->evalDisabled, array('data' => $data));
		else
			$disabled = false;
				
		echo CHtml::htmlButton(CHtml::tag('span', $html,''), array('class' => 'btn btn-default btn-xs', 'ajax' => $ajax, 'data-value' => $data->{$this->attribute} == '1' ? 1 : 0 , 'disabled' => $disabled));
		
	}
	
}
