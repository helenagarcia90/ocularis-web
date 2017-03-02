<?php 

return array( 
	
	'customConfig' => 'config.js',
	
	'autoGrow_onStartup' => true, 
	'height' => 500,
	
	'autoGrow_minHeight' => 500,
		
	'toolbar' => array(
				array('Source','-','Templates'),
	            array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'),
	            array('Find','Replace','-','Maximize'),
	            '/',
	            array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'),
	            array('Styles','Format','FontSize'),
	            array('TextColor','BGColor'),
	            '/',
	            array('NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'),
	            array('Link','Unlink','Anchor'),
	            array('Image','Table','SpecialChar', 'HorizontalRule'),
				'extra' => array(),
				 ),
	
);