<?php

class Editor extends CInputWidget
{
	
	public $editor = null;
	
	public $height = null;
	public $width = null;
	public $preset = 'default';
	public $styles = array();
	public $css_files = array();
		
	public function init(){
				
		if($this->editor === null)
		{
			
			if( isset(Yii::app()->user->editor) && array_key_exists(Yii::app()->user->editor, self::getList()) )
				$this->editor = Yii::app()->user->editor;
			else
				$this->editor = 'ckeditor';
		}
		
		if(Yii::app()->theme !== null)
		{
			if(isset(Yii::app()->theme->config['editor']['css_files']))
			{
				$css_files = Yii::app()->theme->config['editor']['css_files'];
				
				if(!is_array($css_files))
					$css_files = array($css_files);
				
				foreach($css_files as $css_file)
				{
					if( !preg_match('/^(\\/\\/|https?:\\/\\/)/i', $css_file) )
						$this->css_files[] = Yii::app()->theme->baseUrl . '/' . $css_file;
					else
						$this->css_files[] = $css_file;
				}
				
			}
			
			if(isset(Yii::app()->theme->config['editor']['styles']))
			{
				$this->styles = CMap::mergeArray($this->styles, Yii::app()->theme->config['editor']['styles']);
			}
			
			if(isset(Yii::app()->theme->config['editor']['styles']))
			{
				
			}
		}

		
	}
	
	public function run()
	{

		list($name,$id)=$this->resolveNameID();
		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
	
		
		Yii::app()->clientSCript->registerCoreScript('jquery');
		
		$cssAssets = Yii::app()->assetManager->publish( Yii::getPathOfAlias('application.components.widgets.assets.editor.css') );

		
		if($this->editor === 'ckeditor')
		{
			
			$assets = Yii::app()->assetManager->publish( Yii::getPathOfAlias('application.components.widgets.assets.editor.vendors.ckeditor') );
			Yii::app()->clientScript->registerSCriptFile($assets . '/ckeditor.js');
			Yii::app()->clientScript->registerSCriptFile($assets . '/adapters/jquery.js');
			
			//First load from the theme if file is found
			if(file_exists(Yii::app()->theme->basePath.'/editors/ckeditor/'.$this->preset.'.php'))
				$options = require( Yii::app()->theme->basePath.'/editors/ckeditor/'.$this->preset.'.php' );
			//or default set
			else if(file_exists(Yii::getPathOfAlias('application.components.widgets.assets.editor.vendors.ckeditor').'/'.$this->preset.'.php'))
				$options = require(Yii::getPathOfAlias('application.components.widgets.assets.editor.vendors.ckeditor').'/'.$this->preset.'.php');
			else
				$options = array();

			
			$options = CMap::mergeArray($options, array(
							'contentsCss' => CMap::mergeArray(array($cssAssets . '/editor.css'), $this->css_files),
							'filebrowserImageBrowseUrl' => Yii::app()->createUrl('/mediaManager/browse', array('type' => 'images', 'opener' => 'ckeditor')),
							'language' => Yii::app()->language,
			));
			
			if(isset($this->width))
				$options['width'] = $this->width;
				
			if(isset($this->height))
				$options['height'] = $options['autoGrow_minHeight'] = $this->height;
				
			
			if(is_array($this->styles))
			{
				
				$styles = array();
				
				foreach($this->styles as $name => $style)
				{
					$s = array(
						'name' => $name,
					);
					
					if(isset($style['styles']))
						$s['styles'] = $style['styles'];
					
					if(isset($style['classes']))
					{
						$s['attributes'] = array();
						$s['attributes']['class'] = $style['classes']; 
					}
					
					if(isset($style['attributes']))
					{
						if(! array_key_exists('attributes', $s) )
							$s['attributes'] = array();
						
						foreach($style['attributes'] as $key => $attr)
							$s['attributes'][$key] = $attr;
					}
					
					
					if(isset($style['element']))
					{
						if($style['element'] === 'img')
						{
							$s['type'] = 'widget';
							$s['widget'] = 'image';
						}
						else	
							$s['element'] = $style['element'];
					}
					
					$styles[] = $s;
				}
				
				//$options['stylesSet'] = $styles;
				
			}
			
			//reformat extra plugins
			//in order to make the merge possible, extraplugins are set in config files as arrays. Bu CKEditor expects the extra plugins to be separated by comas
			if(isset($options['extraPlugins']))
				$options['extraPlugins'] = implode(',', $options['extraPlugins']);
			
			//ckeditor does not allow array keys for toolbar but we use it for the merge. So here we remove the key	
			if(isset($options['toolbar']['extra']))
			{
					if(count($options['toolbar']['extra']) === 0)
						unset($options['toolbar']['extra']);
					else
						$options['toolbar'] = array_values($options['toolbar']);
			}
			
			Yii::app()->clientScript->registerScript('editor_'.$id, '
					$("#'.$id.'").ckeditor('.CJavaScript::encode($options).');
						CKEDITOR.instances["'.$id.'"].on("change", function(){$("#'.$id.'").trigger("change")});

						CKEDITOR.on("dialogDefinition", function(event){
							
								var editor = event.editor;
							    var dialogDefinition = event.data.definition;
							    var dialogName = event.data.name;
								
							    var tabCount = dialogDefinition.contents.length;
							    for (var i = 0; i < tabCount; i++) {
							        var browseButton = dialogDefinition.contents[i].get("browse");
							
							        if (browseButton !== null) {
							            browseButton.hidden = false;
							            browseButton.onClick = function(dialog, i) {
							                editor._.filebrowserSe = this;
											openMediaManager("type=images&opener=ckeditor&CKEditorFuncNum=" + CKEDITOR.instances[event.editor.name]._.filebrowserFn +"&CKEditor=" + event.editor.name);
							            }
							        }
							    }
								
						});
								
					');
			
			
			
		}
		elseif($this->editor === 'tinymce')
		{
			
			$assets = Yii::app()->assetManager->publish( Yii::getPathOfAlias('application.components.widgets.assets.editor.vendors.tinymce') );
			Yii::app()->clientScript->registerSCriptFile($assets . '/tinymce.min.js');
			
			//First load from the theme if file is found
			if(file_exists(Yii::app()->theme->basePath.'/editors/tinymce/'.$this->preset.'.php'))
				$options = require( Yii::app()->theme->basePath.'/editors/tinymce/'.$this->preset.'.php' );
			//or default set
			else if(file_exists(Yii::getPathOfAlias('application.components.widgets.assets.editor.vendors.tinymce').'/'.$this->preset.'.php'))
				$options = require(Yii::getPathOfAlias('application.components.widgets.assets.editor.vendors.ckeditor').'/'.$this->preset.'.php');
			else
				$options = array();
			
			
			$options = CMap::mergeArray($options, array(
				
					'script_url' => $assets.'/tinymce.min.js',
					
					'file_browser_callback' => 'js:function(field, url, type, win) {
					
						if(type == "image")
						{
							type = "images";
						}

						openMediaManager("type=images&opener=tinymce&type="+type);

						win.field = field;
						return false;
					}',
					
					
					'convert_urls' => false,
					
					'content_css' => CMap::mergeArray(array($cssAssets . '/editor.css'), $this->css_files),
					
					'language' => Yii::app()->language,
					
					
					
					'setup' => "js:function(editor) {
						editor.on('change', function(e) {
							$('#$id').trigger('change');
						});
					
						editor.on('keyup', function(e) {
							$('#$id').trigger('keyup');
						});
					}",
					
			));
			
			if(isset($this->width))
				$options['width'] = $this->width;
				
			if(isset($this->height))
				$options['height'] = $options['autoresize_min_height'] = $this->height;
			
			
			if(is_array($this->styles))
			{
			
				$styles = array();
			
				foreach($this->styles as $name => $style)
				{
					$s = array(
							'title' => $name,
					);
						
					if(isset($style['styles']))
						$s['styles'] = $style['styles'];
						
					if(isset($style['classes']))
					{
						$s['attributes'] = array();
						$s['attributes']['class'] = $style['classes'];
					}
						
					if(isset($style['attributes']))
					{
						if(! array_key_exists('attributes', $s) )
							$s['attributes'] = array();
			
						foreach($style['attributes'] as $key => $attr)
							$s['attributes'][$key] = $attr;
					}
						
						
					if(isset($style['element']))
					{
						if(in_array($style['element'], array('address', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'pre')))
							$s['block'] = $style['element'];
						elseif(in_array($style['element'], array('a', 'embed', 'hr', 'img', 'li', 'object', 'ol', 'table', 'td', 'tr', 'ul')))
							$s['selector'] = $style['element'];
						elseif(in_array($style['element'], array('span')))
							$s['inline'] = $style['element'];
						
					}
						
					$styles[] = $s;
				}
			
				$options['style_formats'] = $styles;
			
			}
				
			
			//reformat toolbars
			
			$i = 1;
			while(isset($options['toolbar'.$i]))
			{
				foreach($options['toolbar'.$i] as &$section)
				{
					$section = implode(' ', $section);
				}
				
				$options['toolbar'.$i] = implode(' | ', $options['toolbar'.$i]);
				
				$i++;
			}
			
			if(isset($options['plugins']))
				$options['plugins'] = implode(' ',$options['plugins']);
			
			Yii::app()->clientScript->registerScript('editor_'.$id, '$("#'.$id.'").tinymce('.CJavaScript::encode($options).');');
		}
		else
		{
			throw new CException("Invalid Editor");
		}
		
		
		if($this->hasModel())
			echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
		else
			echo CHtml::textArea($this->name, $this->value, $this->htmlOptions);
		
	}
	
	public static function getList()
	{
		return array(
			
				'ckeditor' => 'CKEditor',
				'tinymce' => 'TinyMce',
				
		);
	}
	
	
}