<?php 

return CMap::mergeArray(
		
		require_once(Yii::getPathOfAlias('application.components.widgets.assets.editor.vendors.ckeditor').'/default.php'), 
		array(
			
			'image2_alignClasses' => array('image-left', 'image-center', 'image-right')
			
   		)
);