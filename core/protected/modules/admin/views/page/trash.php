<?php
/* @var $model Page */
?>

<?php $this->widget('ModelGridView', array(
	'id'=>'page-grid',
	'dataProvider' => $dataProvider,
	'title' => Yii::t('page', 'CMS Pages') . ' - ' . Yii::t('page', 'Trash'),
	
	'bulkActions' => array(
			'<span class="fa fa-rotate-left"></span> ' . Yii::t('page', "Restore") => $this->createUrl('restore'),
			'delete',
			
	),
		
	'actions' => array(
		
			Yii::t('page', 'Restore') => array(
				'url' => 'Yii::app()->controller->createUrl("restore",array("id" => $data->id_page))',
				'icon' => '<span class="fa fa-rotate-left"></span>',
				'modal' => 'restore',
			),
			
			
			Yii::t('page', 'Delete Permanetly') => array(
					'url' => 'Yii::app()->controller->createUrl("delete",array("id" => $data->id_page))',
					'icon' => '<span class="glyphicon glyphicon-trash"></span>',
					'modal' => 'delete',
			),
		
	),
		
	'headingActions' => array('bulk', 
									Yii::t('page', 'Empty trash') => array(
												'url' => $this->createUrl('emptyTrash'),
												'icon' => '<span class="glyphicon glyphicon-trash"></span>',
												'modal' => true,
											),),
		
	'columns'=>array(
		
		
			
		array(
				'name' => 'title',
				'badge' => 'count($data->allPagesIds)',
		),

		//'category.name',

		array(	
				'class' => 'LanguageColumn',
		),


			
	),
)); ?>