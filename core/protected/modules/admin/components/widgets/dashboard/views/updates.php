<div class="panel panel-primary">
<div class="panel-heading"><?=Yii::t('migration', 'Updates')?></div>
	<table class="table table-striped">
	<thead>
	<tr>
		<th><?=Yii::t('migration', 'Component')?></th>
		<th class="text-center"><?=Yii::t('migration', 'Status')?></th>
	</tr>
	</thead>

	<tbody>

<?php if($coreMigration->checkNewerVersion()):?>
	
<tr class="">
	<td><?= Yii::t('migration', 'Core');?></td>
	<td class="text-center">
	<?php
				echo CHtml::link(CHtml::tag('span', array('class' => 'glyphicon glyphicon-retweet'), '' ) . '&nbsp;&nbsp;'. Yii::t('migration', 'Update'),array('migration/migrate', 'component' => 'core'), array('class' => 'btn btn-warning btn-xs'));
	?>
	</td>
	
	</tr>
<?php endif;?>
	
<?php
		$i=0;
		foreach($modules as $name => $module):
				
				?>
				<tr  class="">	
					<td><?=$module->name;?></td>
						<td class="text-center"><?=CHtml::link(CHtml::tag('span', array('class' => 'glyphicon glyphicon-retweet'), '' ) . '&nbsp;&nbsp;'.Yii::t('migration', 'Update'),array('migration/migrate','component' => $name), array('class' => 'btn btn-warning btn-xs'));?></td>
				</tr>
			<?php $i++;
		endforeach;?>
	</tbody>
</table>
</div>
