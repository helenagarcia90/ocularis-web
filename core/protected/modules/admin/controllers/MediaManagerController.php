<?php

class MediaManagerController extends CController{
		
	public $forbiddenExt = array('php', 'php2', 'php3', 'php4' ,'php5', 'php6');
	
	public $types = array(
		
		'images' => array(
				'ext' => array('jpg','png','jpeg','gif'),
			),		
			
		'medias' => array(
				'ext' => array('mp3', 'mp4', 'avi'),
		),
			
		'docs' => array(
				'ext' => array('doc', 'docx', 'xls', 'xlsx', 'pdf', 'ppt', 'pptx', 'pps', 'ppsx'),
		),
			
		'files' => array(
				'ext' => null,
		),
			
	);
	
	public $_basePath;
	public $_baseUrl;
	public $assetsUrl;
	
	private $ignore = array('index.html', '.thumbs', '.');
	
	public function init()
	{
		$this->_basePath = Yii::getPathOfAlias('medias');
		$this->_baseUrl = Yii::app()->baseUrl . '/medias';
		$this->assetsUrl = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.modules.admin.assets.js'));
	}
	
	private function getDirTree($dir, $curDir)
	{
		
		$dirs = array();
		
		if(is_dir($this->_basePath . DIRECTORY_SEPARATOR . $dir))
		{
			foreach(scandir($this->_basePath . DIRECTORY_SEPARATOR . $dir) as $file)
			{
				
				if($file != '.' && $file != '..' && is_dir($this->_basePath . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $file) && !in_array($file, $this->ignore))
				{
					$dirs[] = array(
						'text' => CHtml::tag('span', array('class' => 'folder', 'data-path' => str_replace('\\', '/', $dir .'/' . $file)), $file),
						'expanded' => $curDir !== '' && strpos($curDir, $dir) === 0,
						'children' => $this->getDirTree($dir . DIRECTORY_SEPARATOR . $file, $curDir),
					);
								
					
				}
			}
		}
		
		return $dirs;
		
	}
		
	private function listFiles($type, $dir)
	{
		
		$files = array();
		$directories = array();


		$path = $this->_basePath . DIRECTORY_SEPARATOR . $dir;

		if(!is_dir($path))
		{
			throw new CHttpException(404);
		}
		elseif(!$this->checkPermission($path))
		{
			throw new CHttpException(403);
		}
		
		
		foreach(scandir($path)  as $file)
		{
			
			//ignore
			if(in_array($file, $this->ignore))
				continue;
			
			$path = $this->_basePath . DIRECTORY_SEPARATOR . $dir . '/' . $file;
			
			if( $file[0] === '.' && $files !== '.' && $file !== '..')
				continue;
			
			if(is_file($path))
			{
			
				
				if( isset($this->types[$type]['ext']) && is_array($this->types[$type]['ext']) )
				{

					$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
					
					if(!in_array($ext, $this->types[$type]['ext']))
						continue;
					
				}
					
				
				$files[] = array( 
								'name' => iconv ( "CP1252" , "UTF-8", $file),
								'path' => iconv ( "CP1252" , "UTF-8", $dir . '/' . $file),
								'type' => 'file',
								'thumb' => $this->getThumb($dir, $file),
								'date' => Yii::app()->locale->dateFormatter->formatDateTime(filemtime($path),"medium"),
								'size' => Yii::app()->format->formatSize(filesize($path)) ,
				);
			
			}
			elseif(is_dir($path))
			{
				
				if($file === '..' && $dir === $type) //ignore updir if at root
					continue;
				
				$directories[] = array(
						'name' => iconv ( "CP1252" , "UTF-8", $file),
						'thumb' => CHtml::tag('span', array('class' => 'glyphicon glyphicon-folder-open')),
						'path' => iconv ( "CP1252" , "UTF-8", $file !== '..' ? $dir . '/' . $file :  dirname($dir)),
						'type' => 'folder',
				);
			}
			
		}
		
		
		return array_merge($directories, $files);
	}
	
	public function actionBrowse($type = 'images', $dir = '')
	{
		
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['bootstrap.min.js'] = false;
		Yii::app()->clientScript->scriptMap['bootstrap.min.css'] = false;

		if($dir === '' && $type !== null)
			$dir = $type;
		
		
		
		$this->renderPartial('manager' , 
						array( 
									'maxUploadSize' => $this->getmaxUploadSize(),
									'type' => $type,
									'dir' => $dir,
									'display' => 'grid',
									'dirs' => array(
												'images' =>  $this->getDirTree('images', $dir),
												'medias' =>  $this->getDirTree('medias', $dir),
												'docs' =>  $this->getDirTree('docs', $dir),
												'files' =>  $this->getDirTree('files', $dir)),									
									'files' => $this->listFiles('images',$dir)
		
							)
			, false, true);
		
	}
	
	public function actionAjaxFiles($dir = '')
	{
		
		$dir = iconv("UTF-8", "CP1252",$dir);
		
		if( ($pos = strrpos($dir, '/')) !== false)
		{
			$type = substr($dir, $pos);
		}
		else
		{
			$type = $dir;
		}
		
		
		$this->renderPartial('fileList', array('files' => $this->listFiles($type,$dir), 'dir' => $dir));
		
	}
	
	public function getMaxFileSize()
	{
		return trim(ini_get('upload_max_filesize'));
	}
	
	public function getmaxUploadSize()
	{
		
		$val = $this->getMaxFileSize();
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		
		return $val;
		
	}
	
	public function getThumb($dir, $file)
	{

		$path = $this->_basePath . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $file;
		
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		
		if( in_array(strtolower($ext), $this->types['images']['ext'] ) )
		{
		
				$destFile = str_replace(array('.png', '.jpeg', '.gif'), '.jpg', strtolower($file));
				
				
				$destination = $this->_basePath . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . '.thumbs' . DIRECTORY_SEPARATOR . $destFile;
				
				Helper::mkdirs(dirname($destination));
				
				if(!file_exists( $destination ))
				{
		
					
					$info = @getimagesize($path);
					
					if($info === false)
						return $this->assetsUrl . '/images/image.jpg';
					
					$width = $info[0];
					$height = $info[1];
					
					switch($info[2])
					{
						case IMAGETYPE_JPEG:
							$source = imagecreatefromjpeg($path);
						break;
								
						case IMAGETYPE_PNG:
							$source = imagecreatefrompng($path);
							break;
								
						case IMAGETYPE_GIF:
							$source = imagecreatefromgif($path);
							break;
								
						default:
							return $this->assetsUrl . '/images/image.jpg';
					
					}
		
								
							$source_width = $width;
							$source_height = $height;
							$source_x = 0;
							$source_y = 0;
					
							$scales = array( 100/$width,
									100/$height );
					
							$scale = min($scales);
										
							$dest_width = $width * $scale;
							$dest_height = $height * $scale;
					
							$out_width = 100;
							$out_height = 100;
								
							$dest_x = ($out_width - $dest_width)/2 ;
							$dest_y = ($out_height - $dest_height)/2 ;
		
			
					
					
					$dest = imagecreatetruecolor($out_width, $out_height);
					$backgroundColor = imagecolorallocate($dest, 255,255,255);
					imagefill($dest, 0, 0, $backgroundColor);
					
					
					
					if(!imagecopyresampled($dest, $source, $dest_x, $dest_y, $source_x, $source_y, $dest_width, $dest_height, $source_width, $source_height))
						throw new CHttpException(404);
					
		
					imagejpeg($dest,$destination,100);
					
					imagedestroy($source);
					imagedestroy($dest);
					
					
				}
				
				$dir = iconv("CP1252", "UTF-8",$dir);
				$destFile = iconv("CP1252", "UTF-8",$destFile);
				
				return  CHtml::image($this->_baseUrl . '/' .  $dir . '/.thumbs/' . $destFile, $file);
				
		}
		else
		{
			return CHtml::tag('span', array('class' => 'fa fa-file-o'));			
		}
		
	}
	
	public function actionAjaxRename($dir, $oldname, $newname)
	{
		
		$newname = iconv("UTF-8", "CP1252",$newname);
		$oldname = iconv("UTF-8", "CP1252",$oldname);
		$dir = iconv("UTF-8", "CP1252",$dir);
		
		$error = false;
				
		$new = $this->_basePath .DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $newname;
		$old = $this->_basePath .DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $oldname;
		
		if(!preg_match("/^[^\/\?\*:;\{\}\\\]+$/", $newname))
		{
			$error = Yii::t('mediaManager', 'The file name contains invalid characters');
		}
		elseif(!preg_match("/^[^\/\?\*:;\{\}\\\]+$/", $oldname))
		{
			$error = "Invalid filename";
		}
		elseif( file_exists($new) )
		{
			$error = Yii::t('mediaManager', 'A file already exist with that name');
		}
		else
		{
		
			if(file_exists($old))
			{
				if(!@rename($old, $new))
					$error = Yii::t('mediaManager', "Error: Could not rename the file");
				else
				{
									 
					$new = $this->_basePath .DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . '.thumbs' . DIRECTORY_SEPARATOR . $newname;
					$old = $this->_basePath .DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . '.thumbs' . DIRECTORY_SEPARATOR . $oldname;
					
					@rename($old, $new);
				}
			}
			else
			{
				$error = "Origin file does not exist";
			}
			
		}
	
		$result = array(
			'success' => $error === false,
			'message' => $error === false ? '' : $error,
		);
		
		echo CJSON::encode($result);
		Yii::app()->end();
	}
	
	
	public function actionAjaxMove($from, $to)
	{

		$from = iconv ( "UTF-8", "CP1252" , $from );
		$to = iconv ( "UTF-8", "CP1252" , $to );
		
		$from = $this->_basePath . DIRECTORY_SEPARATOR . $from;
		$to = $this->_basePath . DIRECTORY_SEPARATOR . $to;
		
		//access to both origin and destination
		if(!$this->checkPermission($from) || !$this->checkPermission($to))
		{
			throw new CHttpException(403);
		}
		
		Helper::mkdirs($to . '/.thumbs/');
		@rename( dirname($from) . '/.thumbs/' .  basename($from),  $to . '/.thumbs/' .  basename($from));
		@rename($from , $to . DIRECTORY_SEPARATOR . basename($from));
				
	}
	
	public function actionAjaxCreateFolder($dir, $name)
	{
				
		
		$name = iconv("UTF-8", "CP1252",$name);
		$dir = iconv("UTF-8", "CP1252",$dir);
		
		$error = false;
		
		$path = $this->_basePath . DIRECTORY_SEPARATOR . $dir;
		
		//access to dir
		if(!$this->checkPermission($path))
		{
			throw new CHttpException(403);
		}
		
		$path.= DIRECTORY_SEPARATOR . $name;
		
		if(!preg_match("/^[^\/\?\*:;\{\}\\\]+$/", $name) || strpos($name, '..') !== false)
		{
			$error = Yii::t('mediaManager', 'The directory name contains invalid characters');
		}
		elseif( file_exists($path) )
		{
			$error = Yii::t('mediaManager', 'A directory already exist with that name');
		}
		else
		{
			Helper::mkdirs($path);
		}
		

		$result = array(
				'success' => $error === false,
				'message' => $error === false ? '' : $error,
		);
		
		echo CJSON::encode($result);
		Yii::app()->end();
		
	}
	
	public function actionAjaxDelete($files)
	{
		
			$files = CJSON::decode($files);
		
			foreach($files as $path)
			{
				$path = iconv ( "UTF-8", "CP1252" , $path );
				
				$path = $this->_basePath . DIRECTORY_SEPARATOR . $path;
				
				
				
				//access to dir
				if(!$this->checkPermission($path))
				{
					continue;
				}
				
				if(is_file($path))
					@unlink($path);
				elseif(is_dir($path))
					Helper::rmrdir($path);

			}
		
	}
	
	private function checkPermission($path)
	{
		
		$path = realpath($path);
		
		//we have permission if we are in our directory or down into it
		if($path !== $this->_basePath && strncmp($path, $this->_basePath, strlen($this->_basePath)) === 0 )
		{
			return true;
		}
		
		return false;
		
	}
	
	public function actionAjaxUpload()
	{
		
		$file = CUploadedFile::getInstanceByName("file");
		
		$dir = Yii::app()->request->getPost('dir','image');
		
		$dir = iconv("UTF-8", "CP1252",$dir);
		
		$path = $this->_basePath . DIRECTORY_SEPARATOR . $dir;
		
		if( ($pos = strpos($dir, "/")) !== false)
			$type = substr($dir, 0, $pos);
		else
			$type = $dir;
		
		if($this->checkPermission($path)) //check permissions
		{
			
			$ext = strtolower($file->extensionName);
			
			// check extension
			if( 
			!in_array($ext, $this->forbiddenExt) // not in forbidden extensions
			&&
				(	
					$this->types[$type]['ext'] === null //type allows all kind of extensions
					|| (is_array($this->types[$type]['ext']) && in_array($ext, $this->types[$type]['ext'])) //the type allows only some extensions
				)
				 
			)
			{
				
					if($file->size <= $this->getmaxUploadSize())
					{
						$filename = $dir = iconv("UTF-8", "CP1252",$file->name);
						
						if($file->saveAs($path . DIRECTORY_SEPARATOR . $filename))
						{
							
							//If we uploaded an image, lets see if we do not need to rotate it
							if(in_array($file->extensionName, $this->types['images']['ext']) )
							{

								$filePath = $path . DIRECTORY_SEPARATOR . $filename;
								$exif = exif_read_data($filePath);
									
								if($exif !== false)
								{
									
									//try to detect orientation if the photo has to be rotated
									if(isset($exif['Orientation']))
									{
										
										$info = @getimagesize($filePath);
										
										if($info !== false)
										{
											$width = $info[0];
											$height = $info[1];
											
											if( $info[2] === IMAGETYPE_JPEG)
											{
													$source = imagecreatefromjpeg($filePath);
											
													$rotate = false;
													switch($exif['Orientation'])
													{
														case 3: //180º
															$source = imagerotate($source, 180, 0);
															$rotate = true;
														break;
											
														case 6: //90º right
															$source = imagerotate($source, -90, 0);
															$rotate = true;
															$old_height = $height;
															$height = $width;
															$width = $old_height;
															break;
											
														case 8: //90º left
															$source = imagerotate($source, 90, 0);
															$old_height = $height;
															$height = $width;
															$width = $old_height;
															$rotate = true;
														break;
											
													}
											
													if($rotate)
													{
														$dest = imagecreatetruecolor($width, $height);
														
														imagecopyresampled($dest, $source, 0, 0, 0, 0, $width, $height, $width, $height);
														imagejpeg($dest,$filePath,100);
														imagedestroy($dest);
												
													}
											
													imagedestroy($source);
											}
										}										
									}
								}
							}
														
							$success = true;
							$message = '';
						}
						else 
						{
							$success = false;
							$message = Yii::t('mediaManager', 'The file could not be saved for an unknown reason');
						}
					}
					else
					{
						$success = false;
						$message = Yii::t('mediaManager', 'The file "{name}" exceeds the maximum size: {maxsize}', array('{name}' => $file->size, '{maxsize}' => Yii::app()->format->formatSize($this->getmaxUploadSize()) ));
					}
			}
			else
			{
				$success = false;
				$message = Yii::t('mediaManager', 'Files with extension "{ext}" are not allowed in this directory. The allowed extensions are: {allowed}', array('{ext}' => $ext, '{allowed}' => implode(', ',$this->types[$type]['ext']) ));
			}
		}
		else
		{
			$success = false;
			$message = "Forbidden directory"; //this should not happen unless the user tries to hack. Don't bother translating the message
		}
		
		echo CJSON::encode(array('success' => $success, 'message' => $message));
		Yii::app()->end();
	}
	
	
}