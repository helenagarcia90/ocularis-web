<div class="form">
<?php 

$form=$this->beginWidget('ActiveForm', array(
		'id'=>'mainForm',
)); 

if($model->hasErrors())
	Yii::app()->user->setFlash('errorSummary',$form->errorSummary($model,null,null,array('class' => 'message error errorSummary')));

$form->fieldGroup('textField' , $model, 'name');
$form->fieldGroup('dropDownList', $model, 'id_category_parent', array('data' => Category::getListData($model->id_category)));
$form->fieldGroup('textArea', $model, 'description', array('rows' => 5));

$this->endWidget();
?>

</div>