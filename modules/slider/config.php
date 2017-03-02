<?php
return array(
	
		
		'name' => Yii::t("SliderModule.main","Sliders"),
		'version' => '1.2',
		
		'adminMenuItems' => array(
				
				array( 
						'label' => 'Sliders',
						'url' => array('/admin/slider/default/index'),
					),
						
				
				),
		
		'imageCachePresets' => array(
			
				'slider' => array(
						'type' => 'crop',
						'width' => 900,
						'height' => 360
				),
				
				'slider-admin' => array(
						'type' => 'crop',
						'width' => 250,
						'height' => 100
				),
				
		),
		
		'auths' => array(
				Yii::t('SliderModule.main', 'Slider') => array(
						Yii::t('auth','View') => 'viewSlider',
						Yii::t('auth','Create') => 'createSlider',
						Yii::t('auth','Update') => 'updateSlider',
						Yii::t('auth','Delete') => 'deleteSlider'
				),
		)
);