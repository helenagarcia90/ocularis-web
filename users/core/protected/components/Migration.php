<?php

abstract class Migration extends CComponent{
	
	public $upgraderXml;
	public $upgraderUrl;
	
	private $_xpath;
	
	private $_componentName;
	private $_tmp_dir;
	private $_curr_version;
	private $_db = null;

	/**
	 * Returns the component's name
	 */
	public function getComponentName()
	{
		
		if($this->_componentName === null)
		{
			$class = get_class($this);
			$this->_componentName = lcfirst( substr($class, 0, strpos($class, "Migration") ) );
		}
		
		return $this->_componentName;
		
	}
	
	public abstract function getComponentLocation();
	
	public abstract function hasDb();
	
	
	public function getTmpDir()
	{
		if($this->_tmp_dir === null)
			$this->_tmp_dir = Yii::getPathOfAlias("runtime").DIRECTORY_SEPARATOR."tmp";
		
		return $this->_tmp_dir;
	}
	
	/**
	 * Returns the version of the files installed onn the server
	 */
	public function getFileVersion()
	{
		
		if($this->componentName === 'core')
			return Yii::app()->version;
		else
			return Yii::app()->moduleManager->getModuleConfig($this->componentName)->version;
	}
	
	public function getLatestVersion()
	{
		
		if($this->xpath === null || $this->xpath === false)
		{
			return $this->fileVersion;
		}
		else 
		{
			$entries = $this->xpath->query("/releases/release[1]/version");
			$cloudVersion = $entries->item(0)->nodeValue;
			
			if(version_compare($cloudVersion, $this->fileVersion, '>'))
				return $cloudVersion;
			else
				return $this->fileVersion;
		}
	}
	

	public function getLatestVersionDownloadUrl()
	{
	
		$entries = $this->xpath->query("/releases/release[1]/url");
		return $entries->item(0)->nodeValue;
	
	}
	
	public function getLatestVersionMd5File()
	{
	
		$entries = $this->xpath->query("/releases/release[1]/md5");
		return $entries->item(0)->nodeValue;
	
	}
	
	public function getCurrentVersion()
	{
		if($this->_curr_version === null)
		{
			
			$command = $this->getDbConnection()->createCommand()->select("version")
			->from("{{migration}}")
			->where("component = :component AND time = (SELECT MAX(time) FROM {{migration}} WHERE component =  :component)",array(":component" => $this->componentName))
			->group("component");
	
			$this->_curr_version = $command->queryScalar();
				
			if($this->_curr_version === false)
				$this->_curr_version = 0;

		}
	
		return $this->_curr_version;
	}
	
	public function checkNewerVersion()
	{
		return version_compare($this->latestVersion, $this->currentVersion, ">"); 
	}
	
	public function getXpath()
	{
		if($this->upgraderUrl === null)
			return null;
				
		if($this->_xpath === null)
		{

			if($this->upgraderXml === null)
				$this->upgraderXml = @file_get_contents($this->upgraderUrl);
			
			if($this->upgraderXml !== false)
			{
				$dom = new DomDocument();
				$dom->loadXML($this->upgraderXml);
				$this->_xpath = new DOMXPath($dom);
			}
			else
				$this->_xpath = false;
		}
		
		return $this->_xpath;
		
		
	}

	
	/**
	 * Download the files from distant server
	 * @return true if operation succeded
	 */
	
	public function downloadFile($url = null)
	{
		if($url === null)
			$url = $this->latestVersionDownloadUrl;
		
		set_time_limit(60*5);
		//empty directory
		@rmdir($this->getTmpDir());
		@mkdir($this->getTmpDir());
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec ($ch);
		curl_close ($ch);
		
		$filename = $this->getComponentName().".zip";
		
		$destination = $this->getTmpDir().DIRECTORY_SEPARATOR.$filename;
		$file = fopen($destination, "w+");
		fputs($file, $data);
		fclose($file);
		
		$md5_file = md5_file($destination);
		
		if($this->latestVersionMd5File !== $md5_file)
			throw new CException(Yii::t('migration' , 'Invalid file checksum'));
		
		return true;
	} 
		
	/**
	 * Extracts the files to a temporary folder
	 * @return true if operation succeded
	 */
	
	public function extractFiles()
	{
		
		$file = $this->getTmpDir().DIRECTORY_SEPARATOR.$this->getComponentName().".zip";
		
		if(!is_file($file))
			throw new CException(Yii::t('migration' , 'Imposible to extract file: file not found'));
				
		$zip = new ZipArchive;
		$res = $zip->open($file);
		
		if ($res === TRUE) {
		    
			$zip->extractTo($this->getComponentLocation());
		    $zip->close();
		    
		    return $this->onAfterExtract();
		}
		else
			throw new CException(Yii::t('migration' , 'Imposible to extract file: Error {error}', array('{error}' => $res) ));
		
	}
	
	public function onAfterExtract()
	{
		return true;
	}
	
	/**
	 * @return true if successfull, false otherwise
	 */
	
	public function installDb()
	{
		return true;
	}
	
	/**
	 * Update actions
	 * 
	 * @param $from the version from which to migrate
	 * 
	 * @return true if successfull, false otherwise 
	 **/
	
	public function updateDb()
	{
		return true;
	}

	public function onUpdateDb()
	{
		return true;
	}
	
	public function afterUpdate()
	{
		return $this->insertMigrationRecord();
	}
	
	public function insertMigrationRecord($version = null, $component = null, $time = null)
	{
	
		
		if($component ===null)
			$component = $this->getComponentName();
	
		if($version === null)
		{
			$version = $this->fileVersion;
		}
		
		if($time ===null)
			$time = new CDbExpression("CURRENT_TIMESTAMP()");
	
		//insert migration into DB
		$tot = $this->getDbConnection()->createCommand()->insert("{{migration}}",array(
				'component'=> $component,
				'version'=> $version,
				'time'=> $time,
		));
		
		return $tot === 1;
	}
	
	


	/**
	 * @return CDbConnection
	 * @throws CException
	 */
	public function getDbConnection()
	{
		if($this->_db===null)
		{
			$this->_db=Yii::app()->getComponent('db');
			if(!$this->_db instanceof CDbConnection)
				throw new CException(Yii::t('yii', 'The "db" application component must be configured to be a CDbConnection object.'));
		}
		return $this->_db;
	}
	
	
}