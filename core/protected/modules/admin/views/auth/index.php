<div class="panel panel-primary">
   <div class="panel-heading">
   	<div class="btn-group pull-right btn-group-panel btn-heading">
   		<?php
   		if(Yii::app()->user->checkAccess('createAuth'))
   			echo CHtml::link('<span class="glyphicon glyphicon-plus"></span>', array('create'), array('class' => 'btn btn-primary btn-sm', 'title' => Yii::t('auth', 'New')));?>
   	</div>
   	<h3 class="panel-title"><?=Yii::t('auth', 'Roles')?></h3>
   	<div class="clearfix"></div>
  	</div>
	<div class="table-responsive">
	
	<?php
		Yii::app()->clientScript->registerScript('auth', '$(".btn, .tooltipped").tooltip();');
	?>
	
	<table class="table table-striped">
	<thead>
	<tr>
		<th><?= Yii::t('auth', 'Role name') ?></th>
		<th><?= Yii::t('auth', 'Actions') ?></th>
	</tr>
	</thead>

	<tbody>
		<?php foreach($roles as $role): ?>
		<tr>
			<td>
				<?=$role->name?>
			</td>
			<td class="col-md text-center">
				<?php if ( $role->name !== 'Superadmin'): ?>
					<?php if(Yii::app()->user->checkAccess('updateAuth'))
					 		echo CHtml::link('<span class="glyphicon glyphicon-edit"></span>', array('update', 'id' => $role->name), array('title' => Yii::t('auth', 'Update') , 'class' => 'btn btn-sm btn-default'));
					?>
					<?php if(Yii::app()->user->checkAccess('deleteAuth'))
						CHtml::link('<span class="glyphicon glyphicon-trash"></span>', array('delete', 'id' => $role->name), array('title' => Yii::t('auth', 'Delete'), 'data-toggle' => 'modal', 'data-target' => '#authModal', 'class' => 'btn btn-sm btn-default'));
					?>
				<?php else:?>
					<span class="glyphicon glyphicon-ban-circle tooltipped" title="<?=Yii::t('auth', 'The permissions of superadmin cannot be edited.')?>"></span>
				<?php endif;?>
			</td>
		</tr>	
		<?php endforeach;?>
	</tbody>
	
	</table>
	
	</div>
	
</div>
<?php $this->widget('Modal', array('id' => 'authModal')); ?>