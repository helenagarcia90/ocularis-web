<?php /* @var $this AdminController */ ?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=Yii::app()->language;?>" lang="<?=Yii::app()->language;?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	 <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php /*<link rel="stylesheet" type="text/css" href="<?= Yii::app()->getModule('admin')->assets ?>/css/styles.css" media="screen, projection" />*/ ?>
	<title><?php echo Yii::app()->name . " : " .  CHtml::encode($this->pageTitle); ?></title>
</head>
	<?php 
	Yii::app()->clientSCript->registerCoreScript('bootstrap');
	Yii::app()->clientSCript->registerCssFile(Yii::app()->getModule('admin')->assets.'/css/custom-template.css');
	//Font-awesome
	Yii::app()->clientSCript->registerCssFile('//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
	?>

<body>

	<div id="mainnav" class="navbar navbar-inverse navbar-static-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?=CHtml::link(Yii::app()->name,array('/admin'), array('class' => 'navbar-brand'));?>	
        </div>
        <div class="navbar-collapse collapse">
        <?php 
        	$this->widget('zii.widgets.CMenu',array(
				'id' => 'topnavbar',
				'encodeLabel' => false,
				'htmlOptions' => array('class' => 'nav navbar-nav navbar-right'),
				'items'=> array(
						array('label' => Yii::t("app","View the front end"),
							'url' => array('/site/index'),
							'linkOptions' => array('target' => '_blank')),
						array('label' => '<span class="glyphicon glyphicon-log-out"></span>',
								'url' => array('/admin/site/logout'),
								'linkOptions' => array('id' => 'logout',
														'title' => Yii::t('admin', 'Logout'),
														'data-placement' => 'bottom',)
							)
					)));

				Yii::app()->clientScript->registerScript('menu',"$('#logout').tooltip();");
        ?>
       </div>
      </div>
    </div>



<div class="container-fluid" id="page">

	
	<div class="row">
	
		<?php 
	
		Yii::app()->clientScript->registerScript('mainlayout',"


			$('#leftpanel > li > a').on('click',function(){
					
					if( $(this).parent().find('> ul').is(':visible') )
					{
						$(this).parent().find(' > ul').slideUp('fast', function(){
							$(this).parent().removeClass('open').find('.description .glyphicon').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right');
						});
						
					}
					else
					{
						if($(this).parent().find('> ul').length>0)
						{
							$('#leftpanel > li.open, #leftpanel > li.active').find(' > ul').slideUp('fast', function(){
								$(this).parent().removeClass('open').find('.description .glyphicon').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right');
							});
					
							$(this).parent().addClass('open').find(' > ul').slideDown('fast', function(){
								$(this).parent().find('.description .glyphicon').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down');
							});
						}
						
					}
						
			
				});

		
			$(document).on('scroll',function(e){
					if($('#toolbar').css('position') == 'relative')
					{
						var scroll = $(window).scrollTop();
					
						offet = $('.main').offset().top - scroll;
						
						if( offet > 0 )
						{
							$('#toolbar').css('top', '0px');
						}
						else
						{
							$('#toolbar').css('top', -1*offet+'px');
						}
					}
			
			
			});

			");
		
		?>
		
		<div class="sidebar">
			<?php $this->widget('PanelMenu',array(
				'submenuHtmlOptions' => array('class' => 'nav nav-stacked leftpanel-submenu'),
				'activateParents' => true,
				'id' => 'leftpanel',
				'htmlOptions' => array('class' => 'nav nav-stacked'),
				'encodeLabel' => false,
			)); ?>
		</div>
			
		<div class="main">
		
			<div id="toolbar" class="navbar navbar-default">
				<div class="container-fluid">
					<div class="navbar-header"><span class="navbar-brand"><?=CHtml::encode($this->pageTitle)?></span></div>
					<?=$this->toolbar;?>
				</div>
			</div>
		
			<div class="page-content">
				<?php if(isset($this->breadcrumbs)):?>
					<?php $this->widget('zii.widgets.CBreadcrumbs', array(
						'links'=>$this->breadcrumbs,
						'homeLink' => CHtml::tag('li',array(),CHtml::link(Yii::t('app','Home'),array('/admin/site/index'))),
						'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
						'inactiveLinkTemplate' => '<li class="active">{label}</li>',
						'tagName' => 'ul',
						'htmlOptions' => array('class' => 'breadcrumb'),
						'separator' => '',
					)); ?><!-- breadcrumbs -->
				<?php endif?>
							
				<?php 	if(Yii::app()->user->hasFlash('success')): ?>
					<div class="alert alert-success fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only"><?=Yii::t('app', 'Close')?></span></button>
						<?=Yii::app()->user->getFlash('success');?>
					</div>
				<?php endif; ?>
				<?php 	if(Yii::app()->user->hasFlash('warning')): ?>
					<div class="alert alert-warning fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only"><?=Yii::t('app', 'Close')?></span></button>
						<?=Yii::app()->user->getFlash('warning');?>
					</div>
				<?php endif; ?>
				<?php 	if(Yii::app()->user->hasFlash('error')): ?>
					<div class="alert alert-danger fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only"><?=Yii::t('app', 'Close')?></span></button> 
						<?=Yii::app()->user->getFlash('error');?>
					</div>
				<?php endif; ?>
				<?php if(Yii::app()->user->hasFlash('errorSummary')): ?>
					<div class="alert alert-danger">
							<?=Yii::app()->user->getFlash('errorSummary');?>
					</div>
				<?php endif;?>
				
				<?php echo $content; ?>
				
				<div id="footer">
					<div class="container-fluid">
						BiiCms by <?=Chtml::link("BbWebConsult","http://www.bbwebconsult.com");?> -  <?=Yii::t('app', 'Version: {version}',array('{version}'  => Yii::app()->version));?> - 
						<?=CHtml::link('<span class="fa fa-bug"></span>&nbsp;' . Yii::t('app', 'Report a bug'), 'https://bitbucket.org/bbwebconsult/biicms/issues/new', array('target' => '_blank'))?>
					</div>
				</div><!-- footer -->
			
			</div>
			
		</div>
	</div>

	<?php
	echo CHtml::tag('div', array('id' => 'mediaManagerModal', 'class' => 'modal fade', 'tabindex' => '-1', 'role' => 'dialog', 'aria-hidden' => 'true', 'style' => 'z-index: 99999'),
	
			CHtml::tag('div', array('class' => 'modal-dialog modal-lg' ),
					CHtml::tag('div', array('class' => 'modal-content'),''						)
			)
	
	);
	
	Yii::app()->clientScript->registerScript('mediaManager', "
		
		function openMediaManager()
		{
			openMediaManager('');
		}
		
		function openMediaManager(append)
		{
			
			url = ".CJavaScript::encode($this->createUrl('/admin/mediaManager/browse')).";
			
			if(url.indexOf('?')>0)
				sep = '&';
			else
				sep = '?';
			
			$('#mediaManagerModal').modal({
				remote: url+sep+append,
				keyboard : false,
			});
			
			$('#mediaManagerModal').on('hidden.bs.modal', function(){ 
					$(this).removeData('bs.modal');
					$('.modal-content', this).html('');
			});
		}
		
	");
	?>
	
</div><!-- page -->
</body>
</html>
