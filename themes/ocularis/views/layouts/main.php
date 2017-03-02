<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=Yii::app()->language;?>" lang="<?=Yii::app()->language;?>">

<?php 

?>

<head>

	<title><?=$this->pageTitle;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link href="<?=Yii::app()->theme->baseUrl;?>/css/style.css" rel="stylesheet" type="text/css" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<?php /*
	<script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/js/superfish.js"></script>
	<script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/js/hoverIntent.js"></script>
	<script type="text/javascript" src="<?=Yii::app()->theme->baseUrl;?>/js/scrolltopcontrol.js"></script>
	*/?>
</head>

<body>
	
	<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

<?php /*Yii::app()->clientScript->registerScript('main','
			$("#mainmenu").superfish();	
	');*/?>
			


	<div id="header">
		<div class="container">
			<div id="logo">
				<img src="<?=Yii::app()->theme->baseUrl;?>/images/ocularis-logo-<?=Yii::app()->language;?>.png" alt="OCULARIS"/>
			</div>
			
			 
			 <?php $this->widget('MenuWidget',array('anchor' => 'top-menu'));?>
						
			<ul id="social">
				<li><a href="http://www.facebook.com/ONG.OCULARIS"  target="_blank"><img src="<?=Yii::app()->theme->baseUrl;?>/images/facebook.png" alt="Siguenos en Facebook"/></a></li>
				<li><a href="http://twitter.com/ONGOCULARIS" target="_blank"><img src="<?=Yii::app()->theme->baseUrl;?>/images/twitter.png" alt="Siguenos en Twitter"/></a></li>
				<li><a href="http://www.teaming.net/ongdocularis" target="_blank"><img src="<?=Yii::app()->theme->baseUrl;?>/images/teaming.png" alt="Ocularis en Teaming"/></a></li>
				<li><a href="http://www.instagram.com/ongocularis" target="_blank"><img src="<?=Yii::app()->theme->baseUrl;?>/images/instagram.png" alt="Siguenos en Facebook" /></a></li>
			</ul>
			
			<?php $this->widget("LangSwitcherWidget");?>
			
			<div id="searchbox">
				<form action="#">
					<input type="text" placeholder="Cercar"/>
					<input type="submit" value=" &gt;&gt; " />
				</form>
			</div>
		</div>
	</div>
	
	<div id="container">
		<?=$content;?>
		
		<?php $this->widget("modules.socialPopUp.widgets.popup.SocialPopUp"); ?>
	</div>
	
	<div id="footer">
		<div class="content">
			<div class="left">
				<p class="copyright">
				&copy; Ocularis associació 2013
				</p>
				<p class="address">
					C/ Camí del Coll Nº7 2-2<br/>
					08870 Sitges (Barcelona)<br/>
					Telf +34 606 515 431<br/>
				</p>
			</div>
			<div class="center">
				<ul id="bottom-menu">
					<li><a href="#">Mapa del Web</a></li>
					<li><a href="#">Avís legal</a></li>
					<li><a href="#">Contacte</a></li>
				</ul>
			</div>
			<div class="right">
				<span style="color: #878787; font-size: 14px; vertical-align: middle;">Dissenyat solidàriament per &nbsp;<img style="position: relative; top: 6px;" src="<?=Yii::app()->theme->baseUrl;?>/images/lazavara.png" alt="L'atzavara" /></span><br/>
				<span style="color: #878787; font-size: 14px; vertical-align: middle;">Desenvolupat solidàriament per <?= $this->id === 'site' && $this->action->id === 'index' ? '<a href="http://www.bbwebconsult.com">BbWebConsult</a>' : 'BbWebConsult '?></span>
			</div>
		</div>
	</div>

	
</body>

</html>