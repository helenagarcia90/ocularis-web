<?php
/* @var $model Lang */
?>

<?php $this->widget('ModelGridView', array(
	'id'=>'lang-grid',
	'dataProvider' => $model->search(),
	'filter'=>$model,
	'actions' => false,
	'headingActions' => false,
	'bulkActions' => false,
	'columns'=>array(

	
		array('name' => 'name',
		),

		array(	'header' => Yii::t('lang', 'Icon'),
				'value' => 'AdminHelper::flag($data);',
				'type'=>'html',
				'filter' =>false,
				'htmlOptions'=>array('style'=>'text-align: center; width: 100px'),
		),

		array(
			'name' => 'default',
			'class' => 'BooleanColumn',
			'url' => 'array("ajaxChangeDefault","lang" => $data->code)',
			'evalDisabled' => '$data->default == 1 || $data->active == 0',
			'visible' => Yii::app()->user->checkAccess('updateLanguage'),
		),
			
		array(
			'name' => 'active',
			'class' => 'BooleanColumn',
			'url' => 'array("ajaxChangeActive","lang" => $data->code)',
			'evalDisabled' => '$data->default == 1',
			'visible' => Yii::app()->user->checkAccess('updateLanguage'),
		),
	),
)); ?>