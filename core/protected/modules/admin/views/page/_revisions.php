<?php 
Yii::app()->clientScript->registerScript('revisions',"


		$('input[name=\"compare[]\"]').on('change',function(){
		
			var active = true;
			var val =$(this).val();
		
			$('input[name=\"with[]\"]').each(function(){

				if( $(this).val() == val )
					active = false;
		
				if(active)
					$(this).show();
				else
					$(this).hide();
				
			});
		
		});
		
		$('input[name=\"with[]\"]').on('change',function(){
		
			var active = false;
			var val =$(this).val();
		
			$('input[name=\"compare[]\"]').each(function(){
		
				if(active)
					$(this).show();
				else
					$(this).hide();
		
				if( $(this).val() == val )
					active = true;
				
			});
		
		});
		
		$('#compare').on('click', function(e){

			e.preventDefault();
		
			v1 = $('input[name=\"compare[]\"]:checked').val();
			v2 = $('input[name=\"with[]\"]:checked').val();
		
			if(v1 != undefined && v2 != undefined)
			{
				$('#compareModal').modal({
					remote: '".$this->createUrl('compare')."?id_v1='+v1+'&id_v2='+v2, 
				});
			}
		
		});
							
		$('#compareModal').on('hidden.bs.modal', function(){ $(this).removeData('bs.modal') });
		
");
?>

<p>
	<button id="compare" class="btn btn-success"><span class="glyphicon glyphicon-transfer"></span> <?=Yii::t('page', 'Compare')?></button>
</p>

<?php
$this->widget('Modal', array('id' => 'compareModal', 'size' => 'modal-full')); 
?> 
 
<div class="table-responsive">
	<table id="page-grid_table"
		class="table table-striped table-hover">
		<thead>
			<tr>
				<th class="col-xs text-center"><?=Yii::t('page', 'V1')?></th>
				<th class="col-xs text-center"><?=Yii::t('page', 'V2')?></th>
				<th><?=Yii::t('page', 'Date')?></th>
				<th><?=Yii::t('page', 'Actions')?></th>
			</tr>
		</thead>
		<tbody>
		
			<?php
			$v1_found = false;
			$v2_found = false;
			$current_found = false;
			$checked_v1 = $checked_v2 = false;

			foreach($revisions as $index => $revision):
				
				if($current->id_page !== $model->id_page)
				{
					$checked_v2 = !$v2_found && ($revision->id_page === $model->id_page || $revision->id_page === $current->id_page);
					$checked_v1 = !$checked_v2 && !$v1_found && ($revision->id_page === $model->id_page || $revision->id_page === $current->id_page);
					
				}
				else {

						if($revision->id_page === $model->id_page)
							$current_found = true;

						
						$checked_v2 = !$v2_found;
						$checked_v1 = !$checked_v2 && !$v1_found && $current_found;
												
				}
				
				if($checked_v1)
					$v1_found = true;
				
				$disabled_v1 = !$v2_found;
				$disabled_v2 = $v1_found;

				if($checked_v2)
					$v2_found = true;
			
			?>
			<tr<?= ($current->id_page === $revision->id_page ? ' class="info"' : ( $model->id_page === $revision->id_page ? ' class="warning"' : '' ))?>>
				<td class="col-xs text-center"><input type="radio" name="compare[]" value="<?=$revision->id_page?>"<?=($checked_v1 ? ' checked="checked"' : '')?><?=($disabled_v1 ? ' style="display:none;"' : '')?>/></td>
				<td class="col-xs text-center"><input type="radio" name="with[]" value="<?=$revision->id_page?>" <?=($checked_v2 ? ' checked="checked"' : '')?><?=($disabled_v2 ? ' style="display:none;"' : '')?>/></td>
				<td>
					<?=Yii::app()->locale->dateFormatter->formatDateTime($revision->update_date,'medium', 'medium');?>
					<?php if($current->id_page === $revision->id_page):?>
						<span class="glyphicon glyphicon-map-marker"></span>
					<?php endif;?>	
					<?php if($model->id_page === $revision->id_page):?>
						<span class="glyphicon glyphicon-file"></span>
					<?php endif;?>
				</td>
				<td>
				<?=CHtml::link('<span class="glyphicon glyphicon-eye-open"></span> '.Yii::t('page', 'Preview'), array('/page/index', 'id' => $revision->id_page, 'preview' => true), array('target' => 'preview', 'class' => 'btn btn-default'))?>
				<?php if($model->id_page !== $revision->id_page):
				
						$recoverParams = array('update');
				
						if($current->id_page !== $revision->id_page)
						{
							$recoverParams['id_revision'] = $revision->id_page;
							$recoverParams['id'] = $revision->id_page_revision;
						}
						else
							$recoverParams['id'] = $revision->id_page;
						
						
				
					?>
					<?=CHtml::link('<span class="fa fa-rotate-left"></span> '.Yii::t('page', 'Recover'), $recoverParams, array('class' => 'btn btn-default'))?>
				<?php endif; ?>
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>

<p>
	<table class="table pull-right" style="width: auto;">
	<tbody>
		<tr>
			<td class="info"><span class="glyphicon glyphicon-file"></span> <?=Yii::t('page', 'Base version')?></td>
		</tr>
		<tr>
			<td class="warning"><span class="glyphicon glyphicon-map-marker"></span> <?=Yii::t('page', 'Currently edited version')?></td>
		</tr>
	</tbody>
</table>
</p>
<div class="clearfix"></div>