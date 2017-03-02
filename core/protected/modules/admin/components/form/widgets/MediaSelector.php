<?php

class MediaSelector extends InputField
{
	
	public $type = 'images';
	public $dir = null;
	public $imagePreset;
	
	public function init()
	{
		parent::init();
		
		if($this->imagePreset === null)
			$this->imagePreset = 'msthumb';
		
		if($this->dir === null)
			$this->dir = '';
		
		if($this->type === null)
			$this->type = 'images';
		
		Helper::mkdirs(Yii::getPathOfAlias('webroot') . '/medias/' . $this->dir);
	}
	
	public function renderInput(){

		
		list($name,$id)=$this->resolveNameID();
		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		
		$this->registerScript();
		
		
		echo CHtml::openTag('div',array('class' => 'mediaSelector', 'id' => $id.'_mediaSelector'));
		
			
		echo CHtml::tag('span', array('class' => 'remove glyphicon glyphicon-remove-sign'),'');
		
		if($this->hasModel())
			$image = Yii::app()->imageCache->createUrl($this->imagePreset, CHtml::resolveValue($this->model, $this->attribute));
		else
			$image = Yii::app()->imageCache->createUrl($this->imagePreset, $this->value);

		if($image == '')
			$image = Yii::app()->getModule('admin')->assets.'/images/noimage.jpg';
		
		echo CHtml::image($image,'',array('class' => 'image'));
		
		echo CHtml::tag('div',array('class' => 'loader'),CHtml::tag('span', array('class' => 'fa fa-spinner fa-spin'),''));
		
		echo CHtml::tag('div',array('class' => 'inner-tip'),Yii::t('mediaSelector','Click to select'),true);
	
		if($this->hasModel())
			echo CHtml::activeHiddenField($this->model,$this->attribute);
		else
			echo CHtml::hiddenField($name,$this->value);
		
		
		echo Chtml::closeTag('div');
		
	}
	
	private function registerScript()
	{
		
		
		$id=$this->htmlOptions['id'];
		
		
		$this->widget("MediaSelectorOpener", array(

		'opener' => '.mediaSelector', 
		'type' => $this->type,
		'dir' => $this->dir,
				
		'callback' => "js:function(url) {
							
								opener.find('.loader').show();
		
								opener.find('img.image').on('load',function(){ opener.find('.loader').hide(); $(this).off('load'); });
		
								thumb = url.replace('/medias/images/','/medias/".Yii::app()->imageCache->cacheDir."/".$this->imagePreset."/');
								opener.find('img.image').attr('src',thumb);
								opener.find('input').val(url);
		
							}
				
		"));
		
		Yii::app()->clientScript->registerCoreScript("jquery");
		
		Yii::app()->clientScript->registerScript('mediaSelector', "
				
				
				$('body').on('mouseenter','.mediaSelector',function(){
	
							$(this).find('.inner-tip').show();
				
							if($(this).find('input').val()!='')
								$(this).find('.remove').show();
					});

				$('body').on('mouseleave','.mediaSelector',function(){
	
							$(this).find('.inner-tip').hide();
							$(this).find('.remove').hide();
					});
				
					
				$('body').on('click','.mediaSelector .remove',function(event){
							event.stopImmediatePropagation();
							$(this).parent().find('img.image').attr('src','".Yii::app()->getModule('admin')->assets.'/images/noimage.jpg'."');
							$(this).parent().find('input').val('');
							$(this).hide();
							
				});
							
		");
		
				
		
		
		
	}
	
}