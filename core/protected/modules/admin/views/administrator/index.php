<?php
/* @var $model Admin */

$this->widget('ModelGridView', array(

	'id'=>'admin-grid',
	'dataProvider' => $dataProvider,
	'columns'=>array(
	'name',
		'email',
	),

)); ?>