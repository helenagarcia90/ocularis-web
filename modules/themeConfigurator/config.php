<?php

return array(
	
		'name' => Yii::t('ThemeConfiguratorModule.main',"Theme Configurator"),
		'version' => '0.2',

		'adminMenuItems' => array(
				array(
						'parent' => 'config',
						'label' => Yii::t("ThemeConfiguratorModule.main", 'Theme Configurator'),
						'url' => array('/admin/themeConfigurator/default/index'),
				)
		),
		
		'auths' => array(
				Yii::t('ThemeConfiguratorModule.main', 'Theme Configurator') => array(
						Yii::t('auth','View') => 'viewThemeConfiguration',
						Yii::t('auth','Update') => 'updateThemeConfiguration',

				),
		)
);