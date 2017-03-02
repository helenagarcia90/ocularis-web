<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap.min.js');
?>

<title><?php echo CHtml::encode($this->pageTitle); ?></title>

<link href="<?=Yii::app()->theme->baseUrl;?>/css/styles.css" rel="stylesheet" type="text/css" />
<?php Yii::app()->clientScript->registerCssFile('//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'); ?>
</head>

<body>

		<?php if($this->id == 'site' && $this->action->id == 'index'):?>
		<div class="slider text-center">

				<?php $this->widget('modules.slider.components.widgets.SliderWidget', array('anchor' => 'home')); ?>
				
		</div>
		<?php endif;?>

		<nav id="topNav" class="navbar navbar-default <?= ($this->id == 'site' && $this->action->id == 'index') ? '' : 'navbar-fixed-top' ?>" role="navigation">
			 <div class="container">
			 
			 	<div class="navbar-header">
			      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainmenu-collapse">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			      </button>
			      
			      <a class="navbar-brand" href="<?=$this->createUrl('/site/index')?>">
			      
			      	<img src="<?=Yii::app()->theme->baseUrl. '/images/ocularis-logo.png'?>" alt="Ocularis">
			      
			      </a>
			    </div>
			  	<div class="collapse navbar-collapse" id="mainmenu-collapse">
				<?php
						$this->widget('webroot.themes.ocularis2.components.BootstrapMenu', array('id' => 'mainmenu',
																	'anchor' => 'top-menu',
																	)); 
				?>
				
				<div class="navright navbar-right">
					<div class="social">					
						<a href="http://www.facebook.com/ONG.OCULARIS" target="_blank"><span class="fa fa-facebook-square"></span></a>
						<a href="https://twitter.com/ONGOCULARIS" target="_blank"><span class="fa fa-twitter-square"></span></a>
						<a href="http://plus.google.com/u/0/+HolaOCULARIS" target="_blank"><span class="fa fa-google-plus-square"></span></a>
						<a href="<?=$this->createUrl('/site/contact')?>"><span class="fa fa-envelope"></span></a>
					</div>
					<div class="socio"><?=CHtml::link('¡Hazte Socio!',array('/socio/socio/create'));?></div>
				</div>
				
				<?php // $this->widget("LangSwitcherWidget");?>
				
				<?php /*
				<form class="navbar-form navbar-right" action="<?=$this->createUrl('/page/search')?>" method="GET" role="search">
			        <div class="form-group">
			          <input type="text" name="keywords" class="form-control" placeholder="Search">
			        </div>
			        <button type="submit" class="btn btn-default">Submit</button>
			      </form> */?>
				</div>
				
				
				
				
			</div>
		</nav>

		<?=$content;?>
		
		<?php /*
		<div id="footer">
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-md-4">
						<?php
						$this->widget('MenuWidget', array( 'htmlOptions' => array('class' => 'menu list-unstyled'),
								'anchor' => 'footer',
						));
						?>
					</div>
					
					<div class="col-sm-6 col-md-4">
					<?php	
						$this->widget('MenuWidget', array( 'htmlOptions' => array('class' => 'menu list-unstyled'),
								'anchor' => 'footer2',
						));
						?>
					</div>
					
					<div class="col-sm-6 col-md-4">
						<p><?=Yii::t('akashicos', 'Síguenos')?></p>
						<?php $this->renderPartial('//includes/social');?>
					</div>
				</div>
			</div>
		</div> */?>
		<div id="credits">
			<div class="container">
				&copy; ONG OCULARIS - Programado solidariamente por <?= CHtml::link('BbWebConsult', 'http://www.bbwebconsult.com', array('target' => '_blank')) ?> y <?= CHtml::link('Tantatech', 'http://www.tantatech.com', array('target' => '_blank')) ?>. Diseñado por <?= CHtml::link('BbWebConsult', 'http://www.bbwebconsult.com', array('target' => '_blank')) ?> en colaboración con <?= CHtml::link('Mientrastanto', 'http://www.mientrastanto.es', array('target' => '_blank')) ?>
			</div>
		</div>
		
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		  ga('create', 'UA-19954523-1', 'auto');
		  ga('send', 'pageview');
		
		</script>
		
</body>
</html>