<?php

class SocioModule extends WebModule
{
	
	public function init()
	{

		$this->setImport(array(
			'socio.models.*',
		));

	}


}
