<?php

class MediaSelectorOpener extends CWidget
{
	
	/**
	 * A JQuery selector that specifies the elemetn that will open the mediaSelector
	 * @var unknown
	 */
	public $opener = null;
	
	/**
	 * The selection mode: 
	 * 'single' => only one media can be selected 
	 * 'multiple' => several medias can be selected
	 */
	public $mode = 'single'; 
	
	/**
	 * A Javascript function that is called after selecting several medias
	 * The function has access to the object "opener", being the element that opened the mediaSelector and: 
	 *  - In the case of 'single' mode, "url", the url of the selected media
	 *  - In case of 'multiple' mode, "urls", an array containing the the urls of the selected medias
	 */
	public $callback = null;
	
	
	public $type = 'images';
	public $dir = '';
	
	public function init()
	{
		Yii::app()->clientSCript->registerCoreScript("jquery");
	}
	
	public function run()
	{

		if($this->opener === null)
			throw new CException("MediaSelectorOpener: The selector must be specified");
		
		if(isset($this->callback))
		{
			switch($this->mode)
			{
				
				case 'single':
					$cbfucntion = 'callback';
				break;
				case 'multiple':
					$cbfucntion = 'callbackMulti';
				break;
				default:
					throw new CException("MediaSelectorOpener: The mode must be 'single' or 'multiple'");
				break;
				
			}
			
			$callback = "window.mediaManagerCallback = {
							". $cbfucntion .": ".CJavaScript::encode($this->callback)."
						};";
		}
		else
			$callback = '';
		
		
	
		Yii::app()->clientScript->registerScript('mediaSelectorOpener', "		
		
		var opener;
		
		{$callback}
				
		$('body').on('click','".$this->opener."',function(){ 
		
			opener = $(this);
			openMediaManager('opener=mediaOpener&type={$this->type}&dir={$this->dir}".($this->mode === 'multiple' ? '&multi=1' : '')."')

		});
		
		");
		
	}
	
	
}