<?php $this->widget('ModelGridView', array(

	'id'=>'page-grid',
	'dataProvider' => $provider,
	'enablePagination' => false,
	'actions' => false,
	'headingActions' => false,
	'bulkActions' => false,
	'title' => $title,
	'filter' => false,
	'pageSize' => $pageSize,
	'columns'=>array(

		array('name' => 'title',
			'value' => 'CHtml::link($data->title,Yii::app()->controller->createUrl("page/update",array("id" => $data->id_page)))',
			'type'=>'html',	
		),
	
		array(	'class' => 'LanguageColumn',
		),

		array(	'name' => 'published_date',
				'value' => 'Yii::app()->locale->dateFormatter->formatDateTime($data->published_date,"short")',
				'filter' =>false,
				'htmlOptions'=>array('width'=>'200px'),
		),
		
		
	),
)); ?>