<div class="row">

<div class="col-md-6">

<form method="POST">

	<?php
		Yii::app()->clientScript->registerScript('elements',"
		
			$('.element').on('change', function(){

				if($('.element:checked').length == 1)
					$('.element:checked').prop('disabled', true);
				else
					$('.element').prop('disabled', false);
		
			});
		
		"); 
	?>

	<div class="form-group">
		<?=CHtml::label(Yii::t('backup', 'Name'), CHtml::getIdByName('name'))?>
		<?=CHtml::textField('name', null, array('class' => 'form-control'))?>
	</div>
	
	<div class="form-group">
		<?=CHtml::label(Yii::t('backup', 'Comment'), CHtml::getIdByName('comment'))?>
		<?=CHtml::textArea('comment', null, array('class' => 'form-control', 'rows' => 6))?>
	</div>

	<div class="form-group">
		<p><strong><?=Yii::t('backup', 'Which elements do you want to backup?')?></strong></p>
		<div class="checkbox">
			<label><input class="element" type = "checkbox" value="1" name="database" checked="checked"/> <?=Yii::t('backup', 'Database')?></label>
		</div>
	
		<div class="checkbox">
			<label><input class="element" type = "checkbox" value="1" name="files"  checked="checked"/> <?=Yii::t('backup', 'Files')?></label>
		</div>
	</div>

	<div class="form-group">
		<?=CHtml::htmlButton(Yii::t('backup', 'Create Backup'), array('class' => 'btn btn-primary', 'type' => 'submit'))?>
	</div>
	
	</form>

</div>

</div>