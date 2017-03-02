<div class="row">

<div class="col-sm-6 col-md-6 col-lg-4">
	
	<?php 
	$this->widget('DirectAccess');
	?>

	<?php 
	$this->widget('Updates');
	?>
	
	<div class="twitter text-center">
		<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/hashtag/BiiCMS" data-widget-id="499299072969150465">Tweets sobre #BiiCMS</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>
	
</div>


<div class="col-sm-6 col-md-6 col-lg-8">
<?php 
		if(Yii::app()->user->checkAccess('viewPost'))
			$this->widget('LastPages', array('type' => Page::TYPE_BLOG, 'title' => Yii::t('dashboard', 'Last posts')));
	?>
	<?php 
		if(Yii::app()->user->checkAccess('viewPage'))
			$this->widget('LastPages', array('title' => Yii::t('dashboard', 'Last pages')));
	?>  
		
</div>



</div>