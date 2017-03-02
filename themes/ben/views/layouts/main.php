<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<title><?php echo CHtml::encode($this->pageTitle); ?></title>

<link href="<?=Yii::app()->theme->baseUrl;?>/css/styles.css" rel="stylesheet" type="text/css" />
<?php Yii::app()->clientSCript->registerCssFile('//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'); ?>
</head>

<body>
	
	<div class="container">

		<div id="header">
			<?php $this->widget('MenuWidget', array('anchor' => 'home-menu', 'id' => 'menu', 'htmlOptions' => array('class' => 'nav nav-pills pull-right') ))?>
	        <h3 class="text-muted">Benoît Bouré</h3>
      	</div>
	
		<div class="row">
		
			
			<div class="main col-md-8 col-md-push-4">
					
					<?=$content;?>
			</div>
			
			<div class="sidebar col-md-3 col-md-pull-8">
				
				<div class="about thumbnail">
					<img src="<?=Yii::app()->theme->baseUrl?>/images/Benoit_Boure.jpg" alt="Benoît Bouré" class="img-circle" />
					
					<div class="caption">
			            <h3>About me</h3>
			            <p class="text-justify">I am a proffessional Freelancer Web developper with skills in Php, MySQL, HTML5, CSS3 and Javascript. I am addict to the <?=CHtml::link('Yii framework', 'http://www.yiiframework.com')?> and getting really interested in <?=CHtml::link('Bootstrap', 'http://www.getbootstrap.com')?>.</p>
			            <p><a href="<?=$this->createUrl('/page/index', array('alias' => 'about-me', 'lang' => 'en'));?>" class="btn btn-primary" role="button">More</a>
			          </div>
					
				</div>
				
				
				<?php
					$lasts = Page::model()->findAll(
						array(
								'condition' => 'type = :type',
								'join' => 'JOIN {{blog_page}} pb ON (t.id_page = pb.id_page  AND pb.id_blog = :id_blog)',
								'limit' => 5,
								'order' => 'published_date DESC',
								'params' => array('type' => Page::TYPE_BLOG, ':id_blog' => 1),
								)
					);
 
				?>
				
				<h3>Last posts</h3>
				<ul id="lasts" class="list-unstyled">
				<?php foreach($lasts as $page):?>
					<li><?=CHtml::link($page->title, array('/page/index', 'id' => $page->id_page))?></li>
				<?php endforeach;?>
				</ul>
				
			</div>
			
		
		</div>
		
		<div id="footer">
			<p>
				<?=CHtml::link('Programación Web Barcelona', 'http://www.bbwebconsult.com')?> - <a href="https://plus.google.com/u/1/109450738129344443884?rel=author" target="_blank">Benoît Bouré</a>
			</p>
		</div>
		
	</div>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-17448715-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>