<?php if($migration->currentVersion > 0): ?>

<p class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;<?=Yii::t('migration', '<strong>{component}</strong> will be updated to version <i>{last_version}</i>',
array(
	'{last_version}' => $migration->latestVersion,
	'{component}' => $migration->componentName, 
))?></p>
		
<?php else: ?>

<p class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;<?=Yii::t('migration', '<strong>{component}</strong> will be installed (version <i>{last_version}</i>)',
array(
	'{last_version}' => $migration->latestVersion,
	'{component}' => $migration->componentName, 
))?></p>

<?php endif;?>

<div class="row">
	<?php if($migration->currentVersion > 0):?>
	<ul style="padding: 0px 15px" class="list-group col-lg-3 col-md-4 col-sm-6">
		
		<?php if(version_compare($migration->fileVersion, $migration->latestVersion, "<")):?>
		<li class="list-group-item" id="download"><?=Yii::t('migration', 'Download Files')?></li>
		<li class="list-group-item" id="extract"><?=Yii::t('migration', 'Extract Files')?></li>
		<?php endif;?>
		<?php if($migration->hasDb()):?>
		<li class="list-group-item" id="updateDb"><?=Yii::t('migration', 'Update Database')?></li>
		<?php endif;?>
	</ul>
	<?php else:?>
		<?php if($migration->hasDb()):?>
		<ul  style="padding: 0px 15px" class="list-group col-lg-3 col-md-4 col-sm-6">
			<li class="list-group-item" id="install"><?=Yii::t('migration', 'Install module')?></li>
		</ul>
		<?php endif;?>
	<?php endif;?>
</div>



<div id="logs" style="display:none" class="well well-sm">
</div>


<p class="buttons">
	<?= CHtml::link(Yii::t('app', 'Cancel'),$this->createUrl('index'),array('class' => 'btn btn-danger', 'name' => 'cancel')); ?>
	<?= CHtml::button(Yii::t('migration', 'Start'), array('id' => 'migrate', 'class' => 'btn btn-success', 'name' => 'confirm')); ?>
</p>

<?php 

if($migration->currentVersion > 0)
	if(version_compare($migration->fileVersion, $migration->latestVersion, "<"))
			$first_step = 'download';
	else
		if($migration->hasDb())
			$first_step = 'updateDb';
		else
			$first_step = 'insertHistory';
else
	$first_step = 'install';

Yii::app()->clientScript->registerScript('migration', "
		

		var first_step = '{$first_step}';
		
		$('body').on('click','#migrate',function(){

			$(this).attr('disabled','disabled');
			$('.buttons').fadeOut('slow');
		
			doStep(first_step);
			
		});
		
		
		function doStep(action)
		{

			
			if(action === 'done')
		 	{
		 		$('#logs').append('<div>".Yii::t('migration','Done')."</div>');
				return;
			}
				
		 	$('#'+action).prepend(\"<span class=\\\"fa fa-spinner fa-spin\\\"></span> &nbsp;\");
		 	
			".
			
			CHtml::ajax(array(
					'url' => $this->createUrl('ajaxMigrate', array('component' => $component)),
					'data' => 'js:"action="+action',
					'dataType' => 'json',
					'success' => 'js:function(data){

										if(!$("#logs").is(":visible"))
										{
											$("#logs").fadeIn("fast");
										}
					
										$("#logs").append("<div>"+data.message+"</div>");

										$("#"+action).removeClass("process");
					
										if(data.next_action!== "error")
										{
											$(".fa", "#"+action).removeClass("fa-spin fa fa-spinner").addClass("glyphicon glyphicon-ok-circle");
											$("#"+action).css("background-color","#DFF0D8");
											doStep(data.next_action);
										}
										else
										{
											$(".fa", "#"+action).removeClass("fa-spin fa fa-spinner").addClass("glyphicon glyphicon-warning-sign");
											$("#"+action).css("background-color","#F2DEDE");
										}
					
								}',
				))
			
			."
		
		}
		
");

?>