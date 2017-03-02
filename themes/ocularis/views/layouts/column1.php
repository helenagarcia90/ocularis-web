
<?php /*  @var $this  Controller */ ?>

<?php $this->beginContent('//layouts/main');?>

	<div id="page">
	
			<div id="column-right">
				<div id="plugins">
						
						<div id="newsletter">
							<img src="<?=Yii::app()->theme->baseUrl;?>/images/newsletter.png" alt="Newsletter"/>
							<h3>Newsletter Ocularis</h3>
							<a href="<?=Yii::app()->createUrl("/mailchimp/default/index");?>" class="link small">Apunta't-hi	</a>
						</div>
						
						<div class="facebook">
							<div class="fb-like-box" data-href="https://www.facebook.com/ONG.OCULARIS" data-width="250"  data-height="250" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="true"></div>
						</div>
						
						<div class="twitter">
							<a class="twitter-timeline" href="https://twitter.com/ONGOCULARIS" data-widget-id="389337807291834368">Tweets por @ONGOCULARIS</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
						</div>
					</div>
			</div>
		<div id="content">
			<?= $content ?>
		</div>
	</div>
	
<?php $this->endContent()?>
