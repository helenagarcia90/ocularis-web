<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<?php
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap.min.js');
?>

<title><?php echo CHtml::encode($this->pageTitle); ?></title>

<link rel="icon" type="image/png" href="<?=Yii::app()->theme->baseUrl?>/images/favicon.png" />
<link href="<?=Yii::app()->theme->baseUrl;?>/css/styles.css" rel="stylesheet" type="text/css" />
<?php Yii::app()->clientScript->registerCssFile('//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'); ?>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body id="home">

        <?php if ($this->id == 'site' && $this->action->id == 'index') {?>
        <div class="slider text-center">
                <?php $this->widget('modules.slider.components.widgets.SliderWidget', array('anchor' => 'home')); ?>
        </div>
        <?php } ?>

        <nav id="topNav" class="navbar">

            <div class="container">

                <div class="navbar-header navbar-default">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/"><img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/logo.png" /></a>
                </div>

                <div id="navbar" class="collapse navbar-collapse navbar-ex1-collapse">

                    <?php $this->widget('webroot.themes.ocularis2.components.BootstrapMenu', array('id' => 'mainmenu',
                    'anchor' => 'top-menu',
                    )); ?>

                    <div class="navright navbar-right">
                        <?php $this->widget("LangSwitcherWidget");?>
                        <div class="social">
                            <a href="http://www.facebook.com/ONG.OCULARIS" target="_blank"><span class="fa fa-facebook-square"></span></a>
                            <a href="https://twitter.com/ONGOCULARIS" target="_blank"><span class="fa fa-twitter-square"></span></a>
                            <a href="http://plus.google.com/u/0/+HolaOCULARIS" target="_blank"><span class="fa fa-google-plus-square"></span></a>
                            <a href="/es/contact"><span class="fa fa-envelope"></span></a>
                        </div>
                        <div class="socio"><a href="/es/hazte-socio">¡Hazte Socio!</a></div>
                    </div>

                </div>

            </div>

        </nav>

		<?=$content;?>

		<div id="credits" class="lift text-center">
            <div class="container">
                <?php $this->widget(
                    'application.components.widgets.MenuWidget',
                    [
                        'id' => 'footer-menu',
                        'anchor' => 'footer',
                    ]
                ); ?>
            </div>
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