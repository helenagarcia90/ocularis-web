<?php 

$this->widget("zii.widgets.CListView",array(
	
		'cssFile' => false,
		'dataProvider' => $dataProvider,
		'itemView' => '_search',
		'template' => '{items}{pager}',
		'pager' => array('cssFile' => false, 'htmlOptions' => array('class' => 'pager'), 'header' => ''), 
));

?>