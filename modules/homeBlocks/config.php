<?php

return array(
	
		'name' => Yii::t('HomeBlocksModule.main',"Home Blocks"),
		'version' => '0.3',

		'adminMenuItems' => array(
				array('label' => Yii::t("HomeBlocksModule.main", 'Home Blocks'),
						'url' => array('/admin/homeBlocks/homeBlock/index'),
				)
		),

		'imageCachePresets' => array(
		
				'home-block' => array(
						'type' => 'crop',
						'width' => 200,
						'height' => 200
				),
		
		),
		
		'auths' => array(
				Yii::t('HomeBlocksModule.main', 'Home Blocks') => array(
						Yii::t('auth','View') => 'viewHomeBlock',
						Yii::t('auth','Create') => 'createHomeBlock',
						Yii::t('auth','Update') => 'updateHomeBlock',
						Yii::t('auth','Delete') => 'deleteHomeBlock'
				),
		)
);