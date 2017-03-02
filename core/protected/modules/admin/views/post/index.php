<?php
/* @var $model Page */

$this->widget('ModelGridView', array(
	'id'=>'page-grid',
	'dataProvider' => $dataProvider,
		
	'bulkActions' => array(
		'<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('page', "Send to trash") => array('url' => $this->createUrl('changeStatus',array('status' => Page::STATUS_TRASH)),
																										'visible' => Yii::app()->user->checkAccess('deletePost')),
		'<span class="glyphicon glyphicon-upload"></span> ' . Yii::t('page', "Publish") => array('url' => $this->createUrl('changeStatus',array('status' => Page::STATUS_PUBLISHED)),
																								'visible' => Yii::app()->user->checkAccess('updatePost')),
		'<span class="glyphicon glyphicon-download"></span> ' . Yii::t('page', "Unpublish") => array('url' => $this->createUrl('changeStatus',array('status' => Page::STATUS_UNPUBLISHED)),
																										'visible' => Yii::app()->user->checkAccess('updatePost')),
		'<span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;' .  Yii::t('page', "Archive") => array('url' => $this->createUrl('changeStatus',array('status' => Page::STATUS_ARCHIVED)),
																												'visible' => Yii::app()->user->checkAccess('updatePost')),
	),
	
	'actions' => array(
			'update',
			Yii::t('page', 'Send to trash') => array(
					'url' => 'Yii::app()->controller->createUrl("changeStatus",array("status" => Page::STATUS_TRASH,"id" => $data->id_page))',
					'icon' => '<span class="glyphicon glyphicon-trash"></span>',
					'modal' => 'trash',
					'visible' => Yii::app()->user->checkAccess('deletePost'),
			),
		
	),
		
	'filters' => array(
			'status' => Page::getFilterStatusListData(),
	),
		
	'headingActions' => array(
				'new',
				'bulk',
				Yii::t('page', 'View trash') => array(
						'url' => $this->createUrl('page/trash'),
						'icon' => '<span class="glyphicon glyphicon-trash"></span>',
						'visible' => Yii::app()->user->checkAccess('deletePost'),
					),),
		
	'columns'=>array(
		
		array(
				'name' => 'status',
				'class' => 'PageStatusColumn',				
		),
			
		array(
				'name' => 'title',
				
		),

		array(	'name' => 'update_date',
				'class' => 'DateColumn',
				'headerHtmlOptions' => array('class' => 'col-lg visible-lg'),
				'htmlOptions' => array('class' => 'col-lg visible-lg'),
				'filterHtmlOptions' => array('class' => 'col-lg visible-lg'),
		),
		array(	'name' => 'published_date',
				'class' => 'DateColumn',
				'headerHtmlOptions' => array('class' => 'col-lg'),
		),
		
		//'status',
			
	),
)); ?>