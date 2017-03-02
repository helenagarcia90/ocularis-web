<?php /* @var $form ActiveForm */ ?>

<div class="row">

	<div class="col-lg-10">
				<?php 
				
				$form->fieldGroup('aliasMaker', $model, 'alias', array('referenceAttribute' => 'title', 'label-col' => 'col-lg-12', 'input-col' => 'col-lg-12'  )); 

				?>
				
				<div style="margin-bottom: 10px;">
				<?php 
				$this->widget('Editor', array(
						'model' => $model,
						'preset' => 'blog',
						'attribute' => 'content',
				));
				?>
				</div>
	</div>
	
	<div class="col-lg-2">
		
				<?php if(count(Yii::app()->languageManager->langs)>1)

				$form->fieldGroup('dropDownList', $model, 'id_category', array('data' => Category::getListData(), 'label-col' => 'col-lg-12', 'input-col' => 'col-lg-12') );
				
				$form->fieldGroup('dropDownList', $model, 'status', array('prompt' => false, 'data' => array(

						Page::STATUS_PUBLISHED => '<span class="glyphicon glyphicon-cloud-upload"></span> '.Yii::t('page', 'Published'),
						Page::STATUS_UNPUBLISHED => '<span class="glyphicon glyphicon-cloud-download"></span> '.Yii::t('page', 'Unpublished'),
						Page::STATUS_ARCHIVED => '<span class="glyphicon glyphicon-folder-open"></span> '.Yii::t('page', 'Archived'),
						Page::STATUS_TRASH => '<span class="glyphicon glyphicon-trash"></span> '.Yii::t('page', 'Trash'),
				
				), 'label-col' => 'col-lg-12', 'input-col' => 'col-lg-12' ));
												
				?>
	
	</div>

</div>



