<?php 

Yii::app()->clientScript->registerCss('share', '
.fb_iframe_widget
{
	top: -5px;
}		
');

Yii::app()->clientScript->registerScript(

	'share',
	"	
	!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"https://platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");
	
	
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
		    js.src = \"//connect.facebook.net/es_LA/all.js#xfbml=1\";
		    fjs.parentNode.insertBefore(js, fjs);
		  }(document, 'script', 'facebook-jssdk'));
	", CClientScript::POS_HEAD

);

?>
<div class="share">
	<a href="<?=$url?>" class="twitter-share-button" data-url="<?=$url?>" data-text="<?=$text?>">Tweet</a>
	<div class="g-plusone" data-href="<?=$url?>"  data-size="medium"></div>
	<div class="fb-like" data-href="<?=$url?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
</div>