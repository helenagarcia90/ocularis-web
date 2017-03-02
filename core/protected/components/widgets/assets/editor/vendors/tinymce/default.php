<?php

return array(
	
		'height' => 500,
		'autoresize_min_height' => 500,

		'resize' => false,
		
		'menubar' => false,
		
		'plugins' => array(
				'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'hr', 'anchor', 'pagebreak', 'readmore',
				'searchreplace', 'code', 'fullscreen', 'table', 'contextmenu', 'template', 'textcolor', 'paste', 'textcolor', 'autoresize'
		),
			
		'toolbar1' => array(
						array('code', 'template'),
						array('cut', 'copy', 'paste', 'undo', 'redo'),
						array('searchreplace', 'fullscreen')
						),
		'toolbar2' => array(
						array('bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript', 'removeformat'),
						array('styleselect'), array('formatselect'), array('fontsizeselect'),
						array('forecolor', 'backcolor')
						),
		'toolbar3' => array(
						array('bullist', 'numlist'),
						array('indent', 'outdent', 'blockquote'),
						array('alignleft', 'aligncenter', 'alignright', 'alignjustify'),
						array('link', 'unlink', 'anchor'),
						array('image',  'table',  'charmap', 'hr'),
						'extra' => array()
						),
		
);