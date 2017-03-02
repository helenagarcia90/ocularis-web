<?php 

$headings = array();

if(isset($_GET['id_page']))
{
	$page = Page::model()->findByPk($_GET['id_page']);
	
	$params = $_GET;
	
	if($page->id_page_parent !== null)
		$params['id_page'] = $page->id_page_parent;
	else
		unset($params['id_page']);
	
	
	$headings = array(
			Yii::t('page', 'Back') => array(
					'url' => $this->createUrl('selector', $params),
					'icon' => '<span class="glyphicon glyphicon-arrow-left"></span>',
					'htmlOptions' => array('class' => 'ajax-link'),
			),
	);
}

$this->widget('ModelGridView', array(
	'id'=> $gridId,
	'dataProvider' => $dataProvider,
	'actions' => false,
	'bulkActions' => false,
	'title' => Yii::t('page', 'Pages'),
	'headingActions' => $headings,
	'columns'=>array(
			array(
					'name' => 'title',
					'value' => '$data->title . ($data->pagesCount(array("condition" => "status <> :status", "params" => array("status" => Page::STATUS_TRASH))) > 0 ? "&nbsp;" . CHtml::link("<span class=\"badge\">".$data->pagesCount(array("condition" => "status <> :status", "params" => array("status" => Page::STATUS_TRASH)))."</span>",Yii::app()->controller->createUrl("selector",array_merge($_GET,array("id_page" => $data->id_page))),array("class" => "ajax-link")) : "") ',
					'type' => 'raw',
					
			),
			array(
				'class' => 'LanguageColumn',
			),	
			array(
				'class' => 'SelectorColumn',
				'header' => '',
				'evalLabel' => '$data->title',
			),
	),
)); 