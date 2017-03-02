<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title"><?=$this->pageTitle?></h4>
</div>

<div  id="mediaManagerContent" class="modal-body">

<?php /* @var $this MediaManagerController */ ?>
<?php 

Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerScriptFile($this->assetsUrl.'/plupload.full.min.js');
Yii::app()->clientScript->registerScriptFile($this->assetsUrl.'/mediaManager.js');
//Yii::app()->clientScript->registerCssFile($this->assetsUrl.'/css/style.css');

$opener = array(
	
		'name' => Yii::app()->request->getQuery('opener'),
		
);

switch(Yii::app()->request->getQuery('opener','mediaOpener'))
{
	
	case 'ckeditor':
		$opener['CKEditorFuncNum'] = Yii::app()->request->getQuery('CKEditorFuncNum',0); 
	break;
	
}

Yii::app()->clientScript->registerScript('manager',"

	assetsUrl = ".CJavaScript::encode($this->assetsUrl).";
		
	manager = new mediaManager({
		baseUrl: '".Yii::app()->baseUrl."',
		uploadUrl: '".$this->createUrl('ajaxUpload')."',
		deleteFileUrl: '".$this->createUrl('ajaxDelete')."',
		createFolderUrl: '". $this->createUrl('ajaxCreateFolder')."',
		renameFileUrl: '".$this->createUrl('ajaxRename')."',
		urlLoadFiles: '".$this->createUrl('ajaxFiles')."',
		urlMoveFile: '".$this->createUrl('ajaxMove')."',
		multi: ".CJavaScript::encode(Yii::app()->request->getQuery('multi','0') == 1).",			
		opener: ".CJavaScript::encode($opener).",
		dir: ".CJavaScript::encode($dir).",
		type: ".CJavaScript::encode(Yii::app()->request->getQuery('type','images')).",
		types: ".CJavaScript::encode($this->types).",
		maxFileSize: ".CJavaScript::encode($this->getmaxUploadSize()).",					
		lang: {
					confirm_delete:  ".CJavaScript::encode(Yii::t('mediaManager', 'Are you sure you want to delete "__filename__"?')).",
					confirm_deleteN: ".CJavaScript::encode(Yii::t('mediaManager', 'Are you sure you want to delete the selected items?')).",
					del : ".CJavaScript::encode(Yii::t('mediaManager', 'Delete')).",
					rename : ".CJavaScript::encode(Yii::t('mediaManager', 'Rename')).",
					newFolder: ".CJavaScript::encode(Yii::t('mediaManager', 'New directory')).",
					file_size_error: ".CJavaScript::encode(Yii::t('mediaManager', 'The file "{name}" exceeds the maximum size: {maxsize}', array('{maxsize}' => $this->getMaxFileSize()))).",
					ext_error: ".CJavaScript::encode(Yii::t('mediaManager', 'Files with extension "{ext}" are not allowed in this directory. The allowed extensions are: {allowed}')).",
			}
	});
	
						
	manager.init();

				
	$('#type').on('change',function(){
			manager.changeType($(this).val());
	
	});
				
");

				
?>

<div class="panel panel-primary panelMediaManager">
   <div class="loader">
   		<span class="label label-warning"><span class="glyphicon glyphicon-refresh"></span>&nbsp;<?=Yii::t('mediaManager', 'Loading ...')?></span>
   	</div>
   <div class="panel-heading">
   		<div class="row">
   		
	   		<div class="col-xs-6">
		   		<?php 
		   		if(  Yii::app()->request->getQuery('type') === null)
		   		{
		   					   		Yii::import('application.modules.admin.components.form.widgets.*'); ?>
		   								<?php $this->widget('DropDown' , array(
		   								'id' => 'type',
		   								'name' => 'type',
		   								'value' => Yii::app()->request->getQuery('type','images'),
		   					   			'data' =>	array(
		   					   				'images' => Yii::t('mediaManager', 'Images'),
		   									'medias' => Yii::t('mediaManager', 'Media'),
		   					   				'docs' => Yii::t('mediaManager', 'Documents'),
		   									'files' => Yii::t('mediaManager', 'Files'),
		   					   			),
		   					   			'options' => array('label' => false, 'prompt' => false),)
		   					   		);
				}	

					
				?>
	   		</div>
	   	
	   		<div class="col-xs-6">
		   		<div class="btn-group pull-right" id="options">
		   			<button type="button" id="refresh" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span></button>
		   			<button type="button" id="pickfiles" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span></button>
		   			<button type="button" id="addFolder" class="btn btn-primary"><span class="glyphicon glyphicon-folder-open"></span></button>
					<button type="button" id="grid" class="btn btn-primary active"><span class="glyphicon glyphicon-th-large"></span></button>
					<button type="button" id="list" class="btn btn-primary"><span class="glyphicon glyphicon-th-list"></span></button>
					<?php /*
					<div class="btn-group">
						<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
							<span class="fa fa-ellipsis-v"></span>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li><a href="#" id="batch-delete">Delete</a></li>
						</ul>
					</div>*/?>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
   
	   
   </div>
	
	<div id="filesContainer">
			
		<div id="files" class="view grid row">
		<?php 
			$this->renderPartial('fileList', $_data_);
		?>
		</div>
	
		<div class="overlay">
		</div>
		
		<div id="upload">
	
			<div class="progress progress-striped active">
			  <div class="progress-bar progress-bar-success" role="progressbar" style="width: 0%">
			    <span></span>
			  </div>
			</div>
					
			<button class="closebox btn btn-primary pull-right" type="button"><?=Yii::t('app','Close')?></button>
			
			<div class="verbose">
			</div>
			
			<div class="clearfix"></div>
		
		</div>
		
	</div>
	

</div>
   
</div>
    
<?php if(Yii::app()->request->getQuery('opener') !== null): ?>
<div class="modal-footer">
    <button class="btn btn-primary" type="button" id="select" disabled="disabled"><?=Yii::t('mediaManager', 'Select')?></button>
</div>
<?php endif;?>


