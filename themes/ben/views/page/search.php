<?php 

$this->widget("zii.widgets.CListView",array(
	
		'cssFile' => false,
		'dataProvider' => $dataProvider,
		'itemView' => '_search',
		'template' => '{items}{pager}',
		'pager' => array('class' => 'BasicPager', 'cssFile' => false, 'htmlOptions' => array('class' => 'pager'), 'header' => ''), 
));

?>