<?php

class BackupCommand extends CConsoleCommand
{
	
		
	public function actionIndex($dir = null, $d = null, $f = null, $name = '', $comment = '')
	{
		set_time_limit(0);
		if($d === null && $f === null)
		{
			$d = $f = true;
		}

		if($dir === null)
		{
			$dir = getcwd();
		}
		
		$dir = rtrim($dir, DIRECTORY_SEPARATOR);
		
		$basePath = realpath(Yii::getPathOfAlias('webroot'). DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'); 
		
		$time = time();
		
		$name = str_replace(' ', '_', $name);
		
		$backupDir = $dir . DIRECTORY_SEPARATOR . ( $name !== '' ? $name . '_' : '' ) . date('Y-m-d_His', $time);
		
		Helper::mkdirs($backupDir);
		

		$xml = new DOMDocument();
		$root = $xml->createElement('backup');
		
		$root->appendChild(new DOMElement('date', date('Y-m-d H:i:s', $time)));
		$root->appendChild(new DOMElement('name', $name));
		$root->appendChild(new DOMElement('comment', $comment));
		
		if($f == 1)
		{
				
			$fileName = $backupDir . DIRECTORY_SEPARATOR . 'files.zip';
			$zip = new ZipArchive();
			$zip->open($fileName, ZipArchive::CREATE);
				
			$files = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator($basePath),
					RecursiveIteratorIterator::LEAVES_ONLY
			);
		
			$baseLen = strlen($basePath)+1;
				
			foreach( $files as $file)
			{
				if($file->getFileName() === '.' || $file->getFileName() === '..')
					continue;
		
				$filePath = $file->getRealPath();
				
				//do not backup the backup file
				if($filePath === $backupDir)
				{
					continue;
				}
				
				$zip->addFile($filePath, substr($filePath, $baseLen));
		
			}
			
			$zip->close();
			
			$root->appendChild(new DOMElement('files', basename($fileName)));
		
		}
		
		if($d == 1)
		{
		
			if ( preg_match( '/mysql:host=([^;]*);dbname=([^;]*)/', Yii::app()->db->connectionString, $matches) )
			{
				$dbfile = $backupDir.DIRECTORY_SEPARATOR.'database.sql';
				exec("mysqldump $matches[2] -h $matches[1] -u".Yii::app()->db->username. " -p".Yii::app()->db->password . " > $dbfile");
					
				$fileName = $dbfile.'.gz';

				$fp = gzopen ($fileName, 'w9');
				$fin = fopen($dbfile, 'r');
				while($chunk = fread($fin, 1024*512))
					gzwrite ($fp, $chunk);
				fclose($fin);
				gzclose($fp);
					
				unlink($dbfile);
					
				$root->appendChild(new DOMElement('database', basename($fileName)));
			}

		
		}
		
		$xml->appendChild($root);
		$xml->save($backupDir . DIRECTORY_SEPARATOR . 'backup.xml');
		
	}
	
	public function actionRestore($file = null, $f = false, $d = false)
	{
		set_time_limit(0);
		if($f)
		{
			if(!is_file ( $file . DIRECTORY_SEPARATOR . 'files.zip') )
			{
				echo "Could not find file $files";
				Yii::log("Could not find file $files", CLogger::LEVEL_ERROR) ;
				
				return 0;
			}
			
			$extractFiles = false;
			
			$basePath = realpath(Yii::getPathOfAlias('webroot'). DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
			
			$zip = new ZipArchive();
			
			$files = $file . DIRECTORY_SEPARATOR . 'files.zip';
			if ($zip->open($files)) {
			
				$zip->extractTo($basePath);
			
				if($zip->close())
					$extractFiles = true;
				else
				{
					echo "Could not open file $files";
					Yii::log("Could not open file $files", CLogger::LEVEL_ERROR) ;
					return 0;
				}
				
			}
			else
			{
				echo "Could not open file $files";
				Yii::log("Could not open file $files", CLogger::LEVEL_ERROR) ;
				return 0;
			}
				
		}
		
		if($d)
		{

			if ( preg_match( '/mysql:host=([^;]*);dbname=([^;]*)/', Yii::app()->db->connectionString, $matches) )
			{
				$dbfile = $file.DIRECTORY_SEPARATOR.'database.sql.gz';
				
				if(!is_file($dbfile))
				{
					echo "Could not find file $dbfile";
					Yii::log("Could not find file $dbfile", CLogger::LEVEL_ERROR) ;
					
					return 0;
				}
				
				
				$fin = gzopen ($dbfile, 'r9');
				$fout = fopen( $file.DIRECTORY_SEPARATOR.'database.sql' , 'w');
				
				while ( $chunk = gzread($fin, 1024*512) )
					fwrite($fout, $chunk);
				
				gzclose($fin);
				fclose($fout);
				
				exec("mysql $matches[2] -h $matches[1] -u".Yii::app()->db->username. " -p".Yii::app()->db->password . " < \"" . $file.DIRECTORY_SEPARATOR . "database.sql\"");
				
				unlink($file.DIRECTORY_SEPARATOR . "database.sql");
				
			}
			
			
		}
		
		return 1;
	}
	
}