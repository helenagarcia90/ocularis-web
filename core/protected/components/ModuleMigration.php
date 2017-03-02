<?php

abstract class ModuleMigration extends Migration{

	public function getComponentLocation()
	{
		$reflector = new ReflectionClass($this);
		$fn = $reflector->getFileName();
		return dirname(dirname($fn));
		
	}	
	
	public function isInstalled()
	{
		return $this->currentVersion !== 0;
	}
	
}