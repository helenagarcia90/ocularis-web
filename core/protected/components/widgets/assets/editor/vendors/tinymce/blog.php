<?php

return CMap::mergeArray(
	
		require_once(dirname(__FILE__).'/default.php'),
		
		array(
				'toolbar3' => array( 'extra' => array('readmore') )
		)
		
);