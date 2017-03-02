<?php 

return CMap::mergeArray(
		
		require_once(dirname(__FILE__).'/default.php'), 

		array(
		
				'extraPlugins' => array( 'readmore' ),
		
				'toolbar' => array( 'extra' => array('ReadMore')),
		
				 
		)
		
);