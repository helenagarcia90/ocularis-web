<?php

class HomeBlocksModule extends WebModule
{
	
	public function init()
	{

		$this->setImport(array(
			'homeBlocks.models.*',
		));
	}
	
}
