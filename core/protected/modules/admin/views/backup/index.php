<div class="panel panel-primary">
   <div class="panel-heading">
	   <div class="btn-group pull-right btn-group-panel btn-heading">
	   		<?php
	   		if(Yii::app()->user->checkAccess('createBackup'))
	   			echo CHtml::link('<span class="glyphicon glyphicon-plus"></span>', array('backup'), array('class' => 'btn btn-primary btn-sm', 'title' => Yii::t('backup', 'Create new backup')));?>
	   	</div>
   		<h3 class="panel-title"><?=Yii::t('backup', 'Backups')?></h3>
   		<div class="clearfix"></div>
   	</div>
	<div class="table-responsive">
	
	<?php
		Yii::app()->clientScript->registerScript('backsups', '$(".btn").tooltip();');
	?>
	
	<table class="table table-striped">
	<thead>
	<tr>
		<th><?= Yii::t('backup', 'Date') ?></th>
		<th><?= Yii::t('backup', 'Name') ?></th>
		<th><?= Yii::t('backup', 'Comment') ?></th>
		<th><?= Yii::t('backup', 'Elements') ?></th>
		<?php  if(Yii::app()->user->checkAccess('restoreBackup') && Yii::app()->user->checkAccess('deleteBackup')): ?>
		<th><?= Yii::t('backup', 'Actions') ?></th>
		<?php endif;?>
	</tr>
	</thead>

	<tbody>
		<?php foreach($backups as $backup):
			$file = basename($backup['path']);
		?>
		<tr>
			<td>
				<?=Yii::app()->locale->dateFormatter->formatDateTime($backup['time'],'medium', 'medium');?>
			</td>
			<td>
				<?=$backup['name'];?>
			</td>
			<td>
				<?=$backup['comment'];?>
			</td>
			<td>
				<?php if(isset($backup['database'])):?>
					<div><span class="fa fa-database"></span>&nbsp; <?=Yii::app()->format->formatSize(  filesize($backup['path'] . DIRECTORY_SEPARATOR . $backup['database'] )  )?></div>
				<?php endif;?>
				<?php if(isset($backup['files'])):?>
					<div><span class="fa fa-files-o"></span>&nbsp; <?=Yii::app()->format->formatSize( filesize($backup['path'] . DIRECTORY_SEPARATOR . $backup['files'] ) )?></div>
				<?php endif;?>	
			</td>
			<?php  if(Yii::app()->user->checkAccess('restoreBackup') && Yii::app()->user->checkAccess('deleteBackup')): ?>
			<td>
				<?php if(Yii::app()->user->checkAccess('restoreBackup')): ?>
					<?=CHtml::link('<span class="glyphicon glyphicon-repeat"></span>', array('restore', 'file' => $file), array('title' => Yii::t('backup', 'Restore') , 'class' => 'btn btn-sm btn-default'))?>
				<?php endif;?>
				
				<?php if(Yii::app()->user->checkAccess('deleteBackup')): ?>
					<?=CHtml::link('<span class="glyphicon glyphicon-trash"></span>', array('delete', 'file' => $file), array('title' => Yii::t('backup', 'Delete'), 'data-toggle' => 'modal', 'data-target' => '#backupModal', 'class' => 'btn btn-sm btn-default'))?>
				<?php endif;?>
			</td>
			<?php endif;?>
		</tr>	
		<?php endforeach;?>
	</tbody>
	
	</table>
	
	</div>
	
</div>

<?php $this->widget('Modal', array('id' => 'backupModal')); ?>
