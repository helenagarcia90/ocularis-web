<?php

class MigrationController extends AdminController
{
	
	public function actionIndex()
	{
		
		if(!Yii::app()->user->checkAccess('viewMigration'))
			throw new CHttpException(403);
		
		$this->breadcrumbs[] = Yii::t('migration', 'Updates');
		
		$coreMigration = new CoreMigration();
		
		$modules = array();
		
		foreach(Yii::app()->moduleManager->getModulesConfig(false) as $name => $module)
		{
			//only modules that have migration logic
			if($module->getMigration() === false)
				continue;

			$modules[$name] = $module;
		}
		
		$dataProvider = new CActiveDataProvider('MigrationModel',array(
			'criteria' => array('order' => 'time DESC')
		));
		
		$this->render('index',array(
				'coreMigration' => $coreMigration,
				'modules' => $modules,
				'migrationDP' => $dataProvider,
				
		));
		
	}
	
	
	public function actionMigrate($component)
	{

		if(!Yii::app()->user->checkAccess('migrateMigration'))
			throw new CHttpException(403);
		
		
			$migration = $this->getMigration($component);

			if($migration === null)
			{
				Yii::app()->user->setFlash("error",Yii::t('migration', 'Migration component missing'));
				$this->redirect(array('migration/index'));
			}
			
			
			if($migration->checkNewerVersion())
			{
			
				$this->breadcrumbs[Yii::t('migration', 'Updates')] = array('index');
				$this->breadcrumbs[] = $migration->componentName;
				
				if($migration->currentVersion > 0)
				{
					$warning = CHtml::tag('p',array(),'<span class="glyphicon glyphicon-warning-sign"></span>
					&nbsp;'.Yii::t('migration', 'Before executing the update process it is highly recomended to make a backup of the whole application (files AND database). If you are not sure of what you are doing, please <a href="{url}">contact us</a> and we will help you.', array('{url}' => 'http://www.bbwebconsult.com/es/contact')));
					$warning .= CHtml::tag('p',array(),CHtml::link(Yii::t('migration', 'Make a Backup'), array('backup/index'), array('class' => 'btn btn-primary')));
					Yii::app()->user->setFlash('warning', $warning);
				
				}
				
				$this->render('migrate',array(
							'migration' => $migration,			
							'component' => $component,
				));
			}
			else
			{
				Yii::app()->user->setFlash("warning",Yii::t('migration', 'The component is up to date'));
				$this->redirect(array('migration/index'));
			}
	}
	
	public function getMigration($component)
	{
		if($component === 'core')
			return new CoreMigration();
		else
		{
			return Yii::app()->moduleManager->getModuleConfig($component)->getMigration();
		}
	}
	
	public function actionAjaxMigrate($component, $action)
	{

		if(!Yii::app()->user->checkAccess('migrateMigration'))
			throw new CHttpException(403);
		
		$migration = $this->getMigration($component);

		if($migration === null)
		{
			echo CJSON::encode(array(
					'next_action' => 'error',
					'message' => Yii::t('migration', 'Migration component missing'),
			));
				
			
			Yii::app()->end();
		}
			
		
		if($migration->checkNewerVersion())
		{
			set_time_limit(60);
			
			$next_action = null;
			$message = '';
			
			try{
			
					switch($action)
					{
						
						case 'insertHistory':
							
							$next_action = 'done';
							$message = Yii::t('migration', 'The module has been updated successfully');
							
						break;
						
						case 'install':
							
							
							if(!$migration->hasDb() || $migration->installDb())
							{
								$next_action = 'done';
								$message = Yii::t('migration', 'The module has been installed successfully');
							}
							else
							{
								$next_action = 'error';
								$message = Yii::t('migration', 'Error while installing the module');
							}
							
						break;
						
						case 'download':
							
							if($migration->downloadFile())
							{
								$next_action = 'extract';
								$message = Yii::t('migration', 'Files Downloaded successfully');
							}
							else
							{
								$next_action = 'error';
								$message = Yii::t('migration', 'Error while downloading the files');
							}
							
						break;
						
						case 'extract':
							
							if($migration->extractFiles())	
							{
								$next_action = $migration->hasDb() ? 'updateDb' : 'done';
								$message = Yii::t('migration', 'Files Extracted successfully');
							}
							else
							{
								$next_action = 'error';
								$message = Yii::t('migration', 'Error while extracting files');
							}
							
						break;
						
						case 'updateDb':
								
							if($migration->updateDb())
							{
								$next_action = 'done';
								$message = Yii::t('migration', 'Database updated successfully');
							}
							else
							{
								$next_action = 'error';
								$message = Yii::t('migration', 'Error while updating Database');
							}
								
						break;
						
						default:
							$next_action = 'error';
							$message = Yii::t('migration', 'Invalid Action');
						break;
					}
			
					
					if($next_action === 'done')
					{
						//empty assets
						Helper::rmrdir(Yii::getPathOfAlias('webroot.assets'),false);
						//empty cache
						Helper::rmrdir(Yii::getPathOfAlias('runtime.cache'), false);
						$migration->afterUpdate();
					}
					
			}
			catch(Exception $e)
			{
				$next_action = 'error';
				$message = $e->getMessage();
			}


		}
		else
		{
			$next_action = 'done';
			$message = Yii::t('migration', 'Last version already installed');
		}

		echo CJSON::encode(array(
				'next_action' => $next_action,
				'message' => $message,
		));
			
		
		Yii::app()->end();
		
		
		
	}
	

}

?>