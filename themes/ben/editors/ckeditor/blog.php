<?php 

return CMap::mergeArray(
		
		require_once(Yii::getPathOfAlias('application.components.widgets.assets.editor.vendors.ckeditor').'/blog.php'), 
		array(
			'customConfig' => '/themes/ben/editors/ckeditor/blog.js',
			'extraPlugins' => array('codesnippet'),
			'toolbar' =>  array(  'extra' => array( 'CodeSnippet') ),
   		)
);