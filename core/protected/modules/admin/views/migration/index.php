<div class="row">

<div class="col-lg-6">

<div class="panel panel-primary">
   <div class="panel-heading"><?=Yii::t('migration', 'Current Version')?></div>
	
	<table class="table table-striped">
	<thead>
	<tr>
		<th><?=Yii::t('migration', 'Component')?></th>
		<th class="text-center"><?=Yii::t('migration', 'Installed Version')?></th>
		<th class="text-center"><?=Yii::t('migration', 'Last Version')?></th>
		<th class="text-center"><?=Yii::t('migration', 'Status')?></th>
	</tr>
	</thead>

	<tbody>

<tr class="">
	<td><?= Yii::t('migration', 'Core');?></td>
	<td class="text-center"><span class="badge"><?= $coreMigration->currentVersion;?></span></td>
	<td class="text-center"><span class="badge"><?= $coreMigration->latestVersion;?></span></td>
	<td class="text-center">
	<?php
	
		if($coreMigration->checkNewerVersion())
		{
			if(Yii::app()->user->checkAccess('migrateMigration'))
				echo CHtml::link(CHtml::tag('span', array('class' => 'glyphicon glyphicon-retweet'), '' ) . '&nbsp;&nbsp;'. Yii::t('migration', 'Update'),array('migrate', 'component' => 'core'), array('class' => 'btn btn-warning btn-xs'));
			else
				echo CHtml::tag('span', array('class' => 'label label-warning'), Yii::t('migration', 'Update'));
		} 
		else
		{
			echo CHtml::tag('span', array('class' => 'label label-success'), Yii::t('migration', 'Up to date'));
		}
	?>
	</td>
	
	</tr>
	
	<?php
		$i=0;
		foreach($modules as $name => $module):
	
				$curr_ver =  $module->getMigration()->currentVersion;
				$latest = $module->getMigration()->latestVersion;
				
				if($curr_ver === 0)
				{
					$action = CHtml::link(CHtml::tag('span', array('class' => 'glyphicon glyphicon-plus'), '' ) . '&nbsp;'. Yii::t('migration', 'Install'),array('migrate','component' => $name), array('class' => 'btn btn-success btn-xs'));
					$curr_ver = $latest = Yii::t('migration','Not installed');
				}
				elseif(version_compare($latest, $curr_ver,">"))
				{
					$action = CHtml::link(CHtml::tag('span', array('class' => 'glyphicon glyphicon-retweet'), '' ) . '&nbsp;&nbsp;'.Yii::t('migration', 'Update'),array('migrate','component' => $name), array('class' => 'btn btn-warning btn-xs'));
				}
				else
				{
					$action = CHtml::tag('span', array('class' => 'label label-success'), Yii::t('migration', 'Up to date'));
				}
				
				?>
				<tr  class="">	
					<td><?=$module->name;?></td>
					<td class="text-center"><span class="badge"><?=$curr_ver;?></span></td>
					<td class="text-center"><span class="badge"><?=$latest;?></span></td>
					<td class="text-center"><?=$action;?></td>
				</tr>
			<?php $i++;
		endforeach;?>
	</tbody>
</table>
		 
</div>


</div>

<div class="col-lg-6">

<div class="panel panel-primary">
   <div class="panel-heading"><?=Yii::t('migration', 'Version History')?></div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'migration-grid',
	'dataProvider' => $migrationDP,
	'selectableRows' => 0,
	'enableSorting' => false,
	'itemsCssClass' => 'table table-stiped',
	'cssFile' => false,
	'template' => '{items}{pager}',
	'columns'=>array(

		'component_name',

		array ( 'name' =>'version',
			'value' => 'CHtml::tag("span", array("class" => "badge"),$data->version)',
			'htmlOptions' => array( 'class' => 'text-center col-md'),
			'headerHtmlOptions' => array( 'class' => 'text-center'),
			'type' => 'raw',
		),

		array( 'name' => 'time',	
				'headerHtmlOptions' => array( 'class' => 'col-lg'),
		)
		
		
	),
)); ?>

</div>

</div>

</div>