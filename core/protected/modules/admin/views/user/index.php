<?php $this->widget('ModelGridView', array(
	'id' => 'usergrid',
	'dataProvider' => $dataProvider,
	'columns'=>array(
		'username',
		'firstname',
		'lastname',
		'email',
	),
)); ?>

