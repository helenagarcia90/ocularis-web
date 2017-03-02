<div class="row">

<div class="col-md-6">

<form method="POST" class="form-horizontal" role="form">
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
		<label class="col-sm-3  control-label"><?=Yii::t('backup', 'Date')?></label>
		<div class="col-sm-9">
			 <p class="form-control-static"><?=Yii::app()->locale->dateFormatter->formatDateTime( $backup['date'] );?></p>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3  control-label"><?=Yii::t('backup', 'Name')?></label>
		<div class="col-sm-9">
			<p class="form-control-static"><?=$backup['name']?></p>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-3  control-label"><?=Yii::t('backup', 'Comment')?></label>
		<div class="col-sm-9">
			<p class="form-control-static"><?=$backup['comment']?></p>
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3 control-label"><?=Yii::t('backup', 'Restore')?></label>
		<div class="col-sm-9">
			<div class="checkbox">
				<label><input class="element" type = "checkbox" value="1" name="database" checked="checked"/> <?=Yii::t('backup', 'Database')?></label>
			</div>
		
			<div class="checkbox">
				<label><input class="element" type = "checkbox" value="1" name="files"  checked="checked"/> <?=Yii::t('backup', 'Files')?></label>
			</div>
		</div>
	</div>
	
	<div class="col-sm-9 col-sm-push-3">
		<p><?=CHtml::htmlButton(Yii::t('backup', 'Restore Backup'),array('id' => 'restore', 'class' => 'btn btn-primary', 'type' => 'submit'))?></p>
		<?php 
		$text = Yii::t('backup', 'Restoring. Please wait ...');
		Yii::app()->clientScript->registerScript('submit',"
		
					
					$('#restore').on('click', function(){
						$(this).prop('disabled', true).addClass('disabled').html('<span class=\"glyphicon glyphicon-refresh fa-spin\"></span> {$text}');
						$(this).parents('form').submit();
					});
					
				"); 
		?>
	</div>

</form>
	
</div>

</div>