<?php
/* @var $this PageController */


?>


<script type="text/javascript">
		
	
		!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
	
	
		window.___gcfg = {lang: '<?=Yii::app()->language?>'};

		  (function() {
		    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		    po.src = 'https://apis.google.com/js/plusone.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
	
		  
		  (function(d, s, id) {
		    var js, fjs = d.getElementsByTagName(s)[0];
		    if (d.getElementById(id)) return;
		    js = d.createElement(s); js.id = id;
		    js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1";
		    fjs.parentNode.insertBefore(js, fjs);
		  }(document, 'script', 'facebook-jssdk'));
	</script>


<div class="post">
	<h1><?= $page->title ?></h1>
	
	<div class="details">
		
		<?php $date = strtotime($page->published_date); ?>
	
		<div class="social">
			<a href="<?=Yii::app()->getBaseUrl(true).Yii::app()->request->url;?>" class="twitter-share-button" data-url="" data-text="<?=$page->title?>">Tweet</a>
			<div class="g-plusone" data-href="<?=Yii::app()->getBaseUrl(true).Yii::app()->request->url;?>"  data-size="medium"></div>
			<div class="fb-like" data-href="<?=Yii::app()->getBaseUrl(true).Yii::app()->request->url;?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
		</div>
		
		<span class="date"><?= Yii::app()->locale->dateFormatter->format("d",$date); ?><span class="month"><?= strtoupper(Yii::app()->locale->dateFormatter->format("LLL",$date)); ?></span><?= Yii::app()->locale->dateFormatter->format("yy",$date); ?></span>
	</div>
	
	
	<article class="article_content">
		<?=$page->content;?>
	</article>
	
		
</div>