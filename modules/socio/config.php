<?php

return array(
	
		
		'name' => Yii::t("SocioModule.main","Socios"),
		'version' => '1.3',
		
		'adminMenuItems' => array(
				
		),
		
		'menuItemTypes' => array(
				array(					
					'route' =>  '/socio/socio/create',
					'id' => 'SocioModule',
					'label' => Yii::t("SocioModule.main", 'Hazte Socio'),
				),
				
		)
		
		
);