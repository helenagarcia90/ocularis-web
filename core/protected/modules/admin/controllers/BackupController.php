<?php

class BackupController extends AdminController
{
	
	const FLAG_DATABASE = 0x0001;
	const FLAG_FILES = 0x0010;
	
	public function actionIndex()
	{
		
		if(!Yii::app()->user->checkAccess('viewBackup'))
			throw new CHttpException(403);
		
		$this->pageTitle = Yii::t('backup', 'Backups');
		
		$dir = $this->getBackupDir();
		
		if(!is_dir($dir))
		{
			Helper::mkdirs($dir);
			Helper::protectDir($dir);
		}
		
		$backups = array();
		
		$files = scandir($dir, SCANDIR_SORT_DESCENDING);
		
		if($files === false)
			throw new CHttpException(500, 'An error occured while reading the backup dir.');
		
		foreach($files as $file)
		{
			$path = $dir . DIRECTORY_SEPARATOR . $file;
			
			if ( ($backup = $this->getBackup($path)) !== null)
				$backups[] = $backup;  
			
				
		}
		
		
		uasort($backups, function($a, $b) {  
			
			$t1 = CDateTimeParser::parse($a['date'], 'yyyy-MM-dd HH:mm:ss');
			$t2 = CDateTimeParser::parse($b['date'], 'yyyy-MM-dd HH:mm:ss');
			
			if($t1 === $t2)
				return 0;
			
			
			return $t1 < $t2 ? 1 : -1;
			
		} ) ;
		
		$this->render('index', array('backups' => $backups));
		
	}
	
	public function actionBackup()
	{
		
		if(!Yii::app()->user->checkAccess('createBackup'))
			throw new CHttpException(403);
		
		if(Yii::app()->request->isPostRequest)
		{
		
			$database = Yii::app()->request->getPost('database',0);
			$files = Yii::app()->request->getPost('files',0);
			$name = Yii::app()->request->getPost('name','');
			$comment = str_replace(array("\r","\n"), array("\\r","\\n"), Yii::app()->request->getPost('comment','') );
			
			 $dir = $this->getBackupDir();
			 
			 Helper::mkdirs($dir);
			 
			 $options = $database == 1 ? ' --d ' : '';
			 $options .= $files == 1 ? ' --f ' : '';
			 
			$cmd = 'php "'.Yii::app()->basePath.'/yiic.php" backup --name="'.$name.'" --comment="'.$comment.'" '.$options.' --dir="'.$dir.'"';
			 
			 if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { 
			 	pclose(popen("start /B $cmd",'r'));
			 }
			 else
			 {
			 	pclose(popen("bash -c \"$cmd\" &",'r'));
			 }
			 
			 Yii::app()->user->setFlash('success', Yii::t('backup', 'The backup process has started. The backup will appear here when it\'s finished.'));
			 $this->redirect(array('index'));
		 
		}
		else
		{
			$this->breadcrumbs[] = $this->pageTitle = Yii::t('backup', 'Create new backup');
			$this->render('backup');
		}
		 
	}
	
	
	public static function getBackupDir()
	{
		//path is set in config
		if(isset(Yii::app()->params->backupDir))
			$dir = Yii::app()->params->backupDir;
		//or parent directory of webroot (for security)
		if(is_writable(dirname(Yii::getPathOfAlias('webroot'))))
			$dir =  dirname(Yii::getPathOfAlias('webroot')).DIRECTORY_SEPARATOR . 'backups';
		//or the webroot
		else
			$dir =  Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . 'backups';
		
		if(!is_dir($dir))
		{
			Helper::mkdirs($dir);
			Helper::protectDir($dir);
		}
		
		return $dir;
	}
	
	public function actionRestore($file)
	{
		
		if(!Yii::app()->user->checkAccess('restoreBackup'))
			throw new CHttpException(403);
		
		$path = $this->getBackupDir()  . DIRECTORY_SEPARATOR . $file;
		
		
		if ( ($backup = $this->getBackup($path)) !== null)
		{

			if(Yii::app()->request->isPostRequest)
			{
				set_time_limit(0);
				//ignore if the user closes the window
				ignore_user_abort(true);
				
				$database = Yii::app()->request->getPost('database',0);
				$files = Yii::app()->request->getPost('files',0);
				
				$options = $database == 1 ? ' --d ' : '';
				$options .= $files == 1 ? ' --f ' : '';
				
				$cmd = 'php "'.Yii::app()->basePath.'\yiic.php" backup restore --file="'.$path.'" '.$options.'';
				
				exec($cmd, $output, $result);
				
				if($result === 1)
					Yii::app()->user->setFlash('success', Yii::t('backup', 'The restore process ended successfully.'));
				else
					Yii::app()->user->setFlash('error', Yii::t('backup', 'Something went wrong. Please consult the logs or contact us for help.'));
				
				$this->redirect(array('index'));
				
			}
			
			Yii::app()->user->setFlash('warning', '<span class="glyphicon glyphicon-warning-sign"></span>&nbsp;' . Yii::t('backup', 'All the data that was changed between this backup and now <strong>will be permanently lost</strong>. Note that the restoration proces might take <strong>several minutes</strong> to complete.'));
			
			$this->render('restore', array('backup' => $backup));
		}
		else
		{
			Yii::app()->user->setFlash('error', Yii::t('backup', 'This backup file does not exist'));
			$this->redirect(array('index'));
		}
			
		
		
	}
	
	
	private function getBackup($file)
	{
		if(is_dir($file) && is_file($file . DIRECTORY_SEPARATOR . 'backup.xml'))
		{
			$dom = new DomDocument();
			$dom->load($file . DIRECTORY_SEPARATOR . 'backup.xml');
			$xpath = new DOMXPath($dom);
			
			$date = $xpath->query("/backup/date");
			$date = $date->length > 0 ? $date->item(0)->nodeValue : date('Y-m-d H:i:s',filectime($path));
			
			$name = $xpath->query("/backup/name");
			$name = $name->length > 0 ? $name->item(0)->nodeValue : '';
			$name = $name == '' ?  basename($file) : $name;
			
			$comment = $xpath->query("/backup/comment");
			$comment = $comment->length > 0 ? $comment->item(0)->nodeValue : '';
			
			$database = $xpath->query("/backup/database");
			$database = $database->length > 0 ? $database->item(0)->nodeValue : null;
			
			$files = $xpath->query("/backup/files");
			$files = $files->length > 0 ? $files->item(0)->nodeValue : null;
			
			return array(
					'path' => $file,
					'date' => $date,
					'time' => CDateTimeParser::parse($date, 'yyyy-MM-dd HH:mm:ss'),
					'name' => $name,
					'comment' => $comment,
					'database' => $database,
					'files' => $files,
				);
		
		}
		
		return null;
		
	}
	
	public function actionDelete($file)
	{

		if(!Yii::app()->user->checkAccess('deleteBackup'))
			throw new CHttpException(403);
		
		$path = $this->getBackupDir()  . DIRECTORY_SEPARATOR . $file;
		
		if ( ($backup = $this->getBackup($path)) !== null)
		{
			if(Yii::app()->request->isPostRequest)
			{
				if(Helper::rmrdir($path))
				{
					Yii::app()->user->setFlash('success', Yii::t('backup', 'The backup has been deleted successfully.'));
				}
				else
				{
					Yii::app()->user->setFlash('error', Yii::t('backup', 'The backup could not be deleted.'));
				}
				$this->redirect(array('index'));
			}
			else
			{
				$this->layout = '/layouts/actionModal';
				$this->render('delete', array('backup' => $backup));
			}
		}
		else
		{
			if(!Yii::app()->request->isPostRequest)
				$this->layout = '/layouts/actionModal';
			
			throw new CHttpException(404);
		}
		
	}
	
}