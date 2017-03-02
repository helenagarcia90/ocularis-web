<?php
return array(
	
		'config' => array( 
		
			'home-cover' => array(
								'type' => 'image',
								'label' => 'Imágen de fondo 1',
								'imagePreset' => 'home-cover-prev',
					),
				
				
			'home-cover-2' => array(
						'type' => 'image',
						'label' => 'Imágen de fondo 2',
						'imagePreset' => 'home-cover-prev',
				),
				
			
				
			'home-text' => array(
					'type' => 'view',
					'view' => 'webroot.themes.ocularis2.config.home-text',
					'label' => 'Contenido de la parte superior de la home',
			),
			
				
			/*
			'home-video' => array(
								'type' => 'text',
								'label' => 'Link del video de la home (youtube)',
			),*/
				
			'rssLimit' => array(
					'type' => 'text',
					'label' => 'Número de entradas del blog en la portada',
			),
				
				
				
			'home-meta-title' => array(
					'type' => 'text',
					'label' => 'Meta título de la home',
			),
				
			'home-meta-keywords' => array(
					'type' => 'text',
					'label' => 'Palabras claves Meta de la home',
			),
			
			'home-meta-description' => array(
					'type' => 'text',
					'label' => 'Descripción meta de la home',
			),
				
		),
		
		'editor' => array(
				'css_files' => array('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css', 'css/styles.css'),
				'styles' =>
				array(
					 	'Imágen izquierda' =>	array('element' => 'img', 'classes' => 'image-left'),
						'Imágen derecha' =>	array('element' => 'img',  'classes' => 'image-right'),
						//'test' => array('element' => 'span', 'styles' => array( 'color' => '#ff0000' )),
				),
		),
		
);