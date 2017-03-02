<?php

class TabView extends CWidget
{
	
	public $cssFile;
	/**
	 * @var string the ID of the tab that should be activated when the page is initially loaded.
	 * If not set, the first tab will be activated.
	 */
	public $activeTab;
	/**
	 * @var array the data that will be passed to the partial view rendered by each tab.
	 */
	public $viewData;
	/**
	 * @var array additional HTML options to be rendered in the container tag.
	 */
	public $htmlOptions;
	/**
	 * @var array tab definitions. The array keys are the IDs,
	 * and the array values are the corresponding tab contents.
	 * Each array value must be an array with the following elements:
	 * <ul>
	 * <li>title: the tab title. You need to make sure this is HTML-encoded.</li>
	 * <li>content: the content to be displayed in the tab.</li>
	 * <li>view: the name of the view to be displayed in this tab.
	 * The view will be rendered using the current controller's
	 * {@link CController::renderPartial} method.
	 * When both 'content' and 'view' are specified, 'content' will take precedence.
	 * </li>
	 * <li>url: a URL that the user browser will be redirected to when clicking on this tab.</li>
	 * <li>data: array (name=>value), this will be passed to the view when 'view' is specified.
	 * This option is available since version 1.1.1.</li>
	 * <li>visible: whether this tab is visible. Defaults to true.
	 * this option is available since version 1.1.11.</li>
	 * </ul>
	 * <pre>
	 * array(
	 *     'tab1'=>array(
	 *           'title'=>'tab 1 title',
	 *           'view'=>'view1',
	 *     ),
	 *     'tab2'=>array(
	 *           'title'=>'tab 2 title',
	 *           'url'=>'http://www.yiiframework.com/',
	 *     ),
	 * )
	 * </pre>
	 */
	public $tabs=array();
	
	/**
	 * Runs the widget.
	*/
	public function run()
	{
		foreach($this->tabs as $id=>$tab)
		if(isset($tab['visible']) && $tab['visible']==false)
			unset($this->tabs[$id]);
	
		if(empty($this->tabs))
			return;
	
		if($this->activeTab===null || !isset($this->tabs[$this->activeTab]))
		{
			reset($this->tabs);
			list($this->activeTab, )=each($this->tabs);
		}
	
		
		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('bootstrap');
		
		$cs->registerScript($this->id.'_',"
			
			var url = decodeURI(window.location);
			var pos = url.indexOf('#');
			if (pos >= 0) {
				var id = url.substring(pos);
				if ($('#{$this->id}').find('>ul a[href=\"'+id+'\"]').length > 0) {
					
					$('#{$this->id} > ul > li').removeClass('active');
					$('#{$this->id} > ul a[href=\"'+id+'\"]').parent().addClass('active');
					$('#{$this->id} .tab-content > div').removeClass('active in');
					$('#{$this->id} .tab-content '+id).addClass('active').addClass('in');
				}
			}		
				
		");
	
		echo CHtml::openTag('div',array('id' => $this->id))."\n";
		$this->renderHeader();
		$this->renderBody();
		echo CHtml::closeTag('div');
	}
	
	
	
	protected function renderHeader()
	{
		echo "<ul class=\"nav nav-tabs\">\n";
		foreach($this->tabs as $id=>$tab)
		{
			$title=isset($tab['title'])?$tab['title']:'undefined';

			$url=isset($tab['url'])?$tab['url']:"#{$id}";

			$options = array();
			if(isset($tab['headerHtmlOptions']))
			{
				$options = $tab['headerHtmlOptions'];
			}
			
			if($id===$this->activeTab)
			{
				if(isset($options['class']))
					$options['class'] .= ' active';
				else
					$options['class'] = 'active';
			}
			
			echo CHtml::tag("li",$options,"<a href=\"{$url}\" data-toggle=\"tab\">{$title}</a>")."\n";
		}
		echo "</ul>\n";
	}
	
	protected function renderBody()
	{
	
		echo CHtml::openTag('div', array('class' => 'tab-content'));
		foreach($this->tabs as $id=>$tab)
		{
	
			echo "<div class=\"tab-pane fade".($id===$this->activeTab ? ' in active' : '')."\" id=\"{$id}\">\n";
			if(isset($tab['content']))
				echo $tab['content'];
			elseif(isset($tab['view']))
			{
				if(isset($tab['data']))
				{
					if(is_array($this->viewData))
						$data=array_merge($this->viewData, $tab['data']);
					else
						$data=$tab['data'];
				}
				else
					$data=$this->viewData;
	
				$this->getController()->renderPartial($tab['view'], $data);
			}
			echo "</div>\n";
				
		}
		echo CHtml::closeTag('div');
	}
	
}