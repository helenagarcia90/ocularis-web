<div class="row">
<div class="col-md-6">
<?php 
	$this->widget('TextField', array(
			'name' => 'name',
			'value' => $name,
			'options' => array('label' => Yii::t('auth', 'Name')),
	
		));


?>
</div>
<div class="col-md-6 form-horizontal">
<?php 
Yii::app()->clientScript->registerScript('permissions', "

	$('.all').on('change', function(){
			$('.check, .all_group').not(':disabled').prop('checked',$(this).prop('checked'));
	});
		
	$('.all_group').on('change', function(){
			$(this).parents('.group').find('.check').not(':disabled').prop('checked', $(this).prop('checked'));
	});	

	$('.check').on('change', function(){
			
			var all = true;
			$(this).parents('.group').find('.check').each(function(){
				all = all && $(this).prop('checked');
			});
		
			$(this).parents('.group').find('.all_group').prop('checked', all);
	});	

");
?>

<div class="form-group group row">
	<div class="checkbox col-sm-12">
		<label>
		  <input type="checkbox" class="all" value="all"><strong><?=Yii::t('auth', 'Check all')?></strong>
		</label>
	</div>
</div>

<?php foreach($groups as $label => $group):

$all = true;
$all_disabled = true;

foreach($group as $operation)
	$all = $all && in_array($operation,array_keys($children));
	$all_disabled = $all_disabled && !Yii::app()->user->checkAccess($operation);
?>

<div class="form-group group row">
	
	<div class="checkbox col-sm-12">
		<label>
		  <input type="checkbox" class="all_group" <?=$all_disabled ? 'disabled="disabled"' : ''?> <?= $all ? 'checked="chcked"' : ''?> value="all"/><strong><?=$label?></strong>
		</label>
	</div>
	
	<?php foreach($group as $label => $operation):?>
	<div  class="checkbox col-lg-3 col-md-4 col-sm-3 col-xs-4">
		<label>
	  		<input type="checkbox" class="check" name="operation[]" <?=!Yii::app()->user->checkAccess($operation) ? 'disabled="disabled"' : '';?> value="<?=$operation?>" <?= in_array($operation,array_keys($children)) ? 'checked="checked"' : ''?>/> <?=$label?>
		</label>
	</div>
	<?php endforeach;?>
	
</div>

<?php endforeach;?>

</div>
</div>