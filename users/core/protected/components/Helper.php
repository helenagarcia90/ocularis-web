<?php

class Helper
{
	
	public static $mysqlDateFormat = 'yyyy-MM-dd HH:mm:ss';
	
	public static function getMetaRobotsListData()
	{
		return array(
				'index, follow' => 'Index, Follow',
				'noindex, follow' => 'No Index, Follow',
				'index, nofollow' => 'Index, No Follow',
				'noindex, nofsollow' => 'No Index, No Follow',
		);
	}

	
	public static function parseLink($value)
	{
	
		if(substr($value,0,4) === 'url:')
		{
			return substr($value, 4);
		}
		elseif($value === '#')
		{
			return false; //no link
		}
		else
		{
			
				
			if( ($pos = strpos($value, '?')) !== false)
			{
				parse_str( substr($value,$pos+1), $return);
				return CMap::mergeArray(array(substr($value,0,$pos)), $return);
			}
			else
			{
				return array($value);
			}
				
		}
	
		
	}
	
	/**
	 * Creates directories recursuvely and adds an empty index.html file 
	 */
	
	public static function mkdirs($dir)
	{

		$dirs=array();
		while(!is_dir($dir))
		{
			array_unshift($dirs, $dir);
			$dir = dirname($dir);
		}
		
		foreach($dirs as $dir)
		{
			mkdir($dir);
			file_put_contents($dir . '/index.html', '');
		}
	}
	
	/**
	 * Protects a dir with an htaccess file
	 */
	
	public static function protectDir($dir)
	{
		if(!is_dir($dir))
			return false;
		
		file_put_contents($dir . DIRECTORY_SEPARATOR . '.htaccess', 'deny from all');
		
	}
	
	/**
	 * Recursively deletes a directory
	 * @param string $dir The path of the directory to be deleted
	 * @param string $rmdir Wether the directory itself should be deleted (false = delte jsut the content). Default to true
	 */
	
	public static function rmrdir($dir, $rmdir = true)
	{
	
		$handler = opendir($dir);
	
		while($f = readdir($handler))
		{
				
			if($f === '.' || $f=== '..')
				continue;
				
			if(is_file($dir . DIRECTORY_SEPARATOR . $f))
			{
				@unlink($dir . DIRECTORY_SEPARATOR . $f);
			}
			elseif(is_dir($dir . DIRECTORY_SEPARATOR . $f))
				self::rmrdir($dir . DIRECTORY_SEPARATOR . $f);
				
		}
		
		closedir($handler);
		
		if($rmdir)
		{
			@rmdir($dir);
		}
		
		return true;
	}
	
}