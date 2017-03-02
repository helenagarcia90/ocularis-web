<?php
/* @var $model Page */

$this->widget('ModelGridView', array(
	'id'=>'page-grid',
	'dataProvider' => $dataProvider,
		
	'bulkActions' => array(
		'<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('page', "Send to trash") => array('url' => $this->createUrl('changeStatus',array('status' => Page::STATUS_TRASH)),
																										'visible' => Yii::app()->user->checkAccess('deletePage')),
		'<span class="glyphicon glyphicon-upload"></span> ' . Yii::t('page', "Publish") => array('url' => $this->createUrl('changeStatus',array('status' => Page::STATUS_PUBLISHED)),
																								'visible' => Yii::app()->user->checkAccess('updatePage')),
		'<span class="glyphicon glyphicon-download"></span> ' . Yii::t('page', "Unpublish") => array('url' => $this->createUrl('changeStatus',array('status' => Page::STATUS_UNPUBLISHED)),
																										'visible' => Yii::app()->user->checkAccess('updatePage')),
		'<span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;' .  Yii::t('page', "Archive") => array('url' => $this->createUrl('changeStatus',array('status' => Page::STATUS_ARCHIVED)),
																												'visible' => Yii::app()->user->checkAccess('updatePage')),
	),
	
	'actions' => array(
			'update',
			Yii::t('page', 'Details') => array(
					'url' => 'Yii::app()->controller->createUrl("index",array("id_page" => $data->id_page))',
					'icon' => '<span class="glyphicon glyphicon-eye-open"></span>',
			),
			Yii::t('page', 'Send to trash') => array(
					'url' => 'Yii::app()->controller->createUrl("changeStatus",array("status" => Page::STATUS_TRASH,"id" => $data->id_page))',
					'icon' => '<span class="glyphicon glyphicon-trash"></span>',
					'modal' => 'trash',
					'visible' => Yii::app()->user->checkAccess('deletePage'),
			),
		
	),
		
	'filters' => array(
			'status' => Page::getFilterStatusListData(),
			'id_category' => CMap::mergeArray(array(Yii::t('page', 'Category')), Category::getListData()),
	),
		
	'headingActions' => array('new', 'bulk',
				Yii::t('page', 'View trash') => array(
						'url' => $this->createUrl('trash'),
						'icon' => '<span class="glyphicon glyphicon-trash"></span>',
						'visible' => Yii::app()->user->checkAccess('deletePage'),
					),),
		
	'columns'=>array(
		
		array(
				'name' => 'status',
				'class' => 'PageStatusColumn',			
				'htmlOptions' => array('class' => 'hidden-xs'),
				'headerHtmlOptions' => array('class' => 'hidden-xs'),
				'filterHtmlOptions' => array('class' => 'hidden-xs'),
		),
			
		array(
				'name' => 'title',
				'badge' => '$data->pagesCount(array("condition" => "status <> :status", "params" => array("status" => Page::STATUS_TRASH)))',
				'badgeUrl' => 'Yii::app()->controller->createUrl("index",array("id_page" => $data->id_page))',
				
		),

		array(	
				'class' => 'LanguageColumn',
		),

		array(
				'header' => Yii::t('page', 'Assoc.'),
				'value' => 'CHtml::link(CHtml::tag("span",array("class" => "badge"),$data->pagesAssocsCount),array("index", "id_page_from" => $data->id_page))',
				'type' => 'raw',
				'headerHtmlOptions' => array('class' => 'col-sm text-center hidden-xs'),
				'htmlOptions' => array('class' => 'col-sm text-center hidden-xs'),
				'filterHtmlOptions' => array('class' => 'col-sm hidden-xs'),
				
				'tooltip' => Yii::t('page', 'Associated pages'),
				'visible' => count(Yii::app()->languageManager->langs)>1,
				
		),
		array(	'name' => 'update_date',
				'class' => 'DateColumn',
				'headerHtmlOptions' => array('class' => 'col-lg visible-lg'),
				'htmlOptions' => array('class' => 'col-lg visible-lg'),
				'filterHtmlOptions' => array('class' => 'col-lg visible-lg'),
		),
		array(	'name' => 'published_date',
				'class' => 'DateColumn',
				'headerHtmlOptions' => array('class' => 'col-lg visible-lg'),
				'htmlOptions' => array('class' => 'col-lg visible-lg'),
				'filterHtmlOptions' => array('class' => 'col-lg visible-lg'),
		),
		
		//'status',
			
	),
)); ?>