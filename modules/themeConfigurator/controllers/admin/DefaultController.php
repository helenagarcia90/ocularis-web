<?php

class DefaultController extends AdminController
{
	
	
	public function actionIndex()
	{
		
		$this->pageTitle = Yii::t('ThemeConfiguratorModule.main', 'Theme Configurator');
		
		if(!Yii::app()->user->checkAccess('viewThemeConfiguration'))
			throw new CHttpException(403); 
		
		
		$this->toolbar = $this->widget('Toolbar',
				array(
						'buttons' => array(
							'save' => array(
									'submit' => 'mainForm',
									'value' => '<span class="glyphicon glyphicon glyphicon-floppy-disk"></span> ' . Yii::t('toolbar', "Save"),
									'id' => 'save',
									'htmlOptions' => array('class' => 'btn-success'),
					
							),
				))
					
				,true);

		
		$config = Yii::app()->theme->config;

		$themeConfig = ThemeConfig::model()->findAll();
		
		if(isset($_POST['ThemeConfig']))
		{
			if(!Yii::app()->user->checkAccess('viewThemeConfiguration'))
				throw new CHttpException(403);
			
			$result = true;
			
			foreach($_POST['ThemeConfig'] as $name => $value)
			{
				if(isset($themeConfig[$name]))
				{
					$themeConfig[$name]->value = $value;
					
				}
				else {
					$themeConfig[$name] = new ThemeConfig();
					$themeConfig[$name]->name = $name;
					$themeConfig[$name]->value = $value;
				}
				
				$result &= $themeConfig[$name]->save();
			}
			
			if($result)
				Yii::app()->user->setFlash('success', Yii::t('ThemeConfiguratorModule.main', 'The configuration was saved successfully'));
			else
				Yii::app()->user->setFlash('error', Yii::t('ThemeConfiguratorModule.main', 'An error occured while saving the configuration'));
		}
		
		
				
		$this->render('form', array(
			'config' => isset($config['config']) ? $config['config'] : array(),
			'themeConfig' => $themeConfig,
		));
		
	}
	
	
}