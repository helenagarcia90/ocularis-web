<?php $this->layout = '//layouts/main';

if(Yii::app()->themeConfig->get('home-meta-title') != '')
	$this->pageTitle = Yii::app()->themeConfig->get('home-meta-title');
else
	$this->pageTitle = Yii::app()->name;

if(Yii::app()->themeConfig->get('home-meta-description') != '')
	Yii::app()->clientScript->registerMetaTag(Yii::app()->themeConfig->get('home-meta-description'), 'description');

if(Yii::app()->themeConfig->get('home-meta-keywords') != '')
	Yii::app()->clientScript->registerMetaTag(Yii::app()->themeConfig->get('home-meta-keywords'), 'keywords');

if(Yii::app()->themeConfig->get('home-cover-2') != '')
{
	Yii::app()->clientScript->registerCss('background1', '

		#teaser
		{
			background-image: url(\''. Yii::app()->imageCache->createUrl('home-cover',Yii::app()->themeConfig->get('home-cover-2')).'\');
			
		}

');
}

if(Yii::app()->themeConfig->get('home-cover') != '')
{
	
	Yii::app()->clientScript->registerCss('background2', '
						
			.features
			{
				background-repeat: no-repeat;
				background-position: center center;
				background-attachment: fixed;
				background-size: cover;
				background-image: url(\''. Yii::app()->imageCache->createUrl('home-cover',Yii::app()->themeConfig->get('home-cover')).'\') !important;
			}
						
			#main .features h3,
			#main .features p
			{
				color: #fff !important;
			}
						
		');

}

?>

<div id="main">
			
			<div class="features">
				<div class="container">
				<div class="row">
					
					<?php 
					
					$blocks = HomeBlock::model()->active()->findAll(array('limit' => 4, 'order' => 'position'));
					
					$count = count($blocks);
					
					switch($count)
					{
						case 1:
							$class= "col-sm-12 col-md-12";
						break;
						case 2:
							$class= "col-sm-6 col-md-6";
						break;
						case 3:
							$class= "col-sm-12 col-md-4";
						break;
						case 4:
							$class= "col-sm-6 col-md-3";
						break;
					}
					
					foreach($blocks as $block):
					?>
					
					<div class="col <?=$class?> text-center">
					
						<?php
						
							if($block->target != null)
							{
								$linkOptions = array('target' => $block->target);
							}
							else
							{
								$linkOptions = array();
							}
							
						
							if($block->image !== null)
							{
								$image = CHtml::image( Yii::app()->imageCache->createUrl('home-block',$block->image), $block->title, array('class' => 'img-thumbnail img-circle'));
								if($block->link !== null)
								{
									echo CHtml::link($image, Helper::parseLink($block->link),$linkOptions);
								}
								else 
									echo $image;
							} 
						?>
						<h3><?=$block->title ?></h3>
						<?=$block->content;?>
						
						<?php if($block->link !== null):?>
							<?=CHtml::link(Yii::t('ocularis', 'Leer más'), Helper::parseLink($block->link), CMap::mergeArray($linkOptions, array('class' => 'btn btn-alter')))?>
						<?php endif;?>
					</div>
					
					<?php endforeach;?>
					
				</div>
				</div>
		</div>
		</div>
		
		<div class="columns container-fluid">
			<div class="container">
				<div class="row">
				<div class="col-md-4">
					<?php $this->widget('modules.rssReader.components.widgets.RssReaderWidget',
								array('url' => 'http://ocularisassociacio.blogspot.com/feeds/posts/default?alt=rss', 'limit' => (int)Yii::app()->themeConfig->get('rssLimit'))); ?>
				</div>
				
				<div class="col-md-4">
					<div id="soci">
						<h3><?=Yii::t('SocioModule.main', 'Become a associate of Ocularis')?></h3>
						
						<p class="image-box text-center">
							<img src="<?=Yii::app()->theme->baseUrl;?>/images/hazte-socio.jpg" class="img-responsive" alt="Fes-te soci">
						</p>
						
						<p>
							El apoyo individual de personas como tú nos permite formar a médicos oftalmólogos y a ópticos para que sepan curar y devolver la vista a quienes más lo necesitan. Con tu aportación tú puedes devolver un vida digna a las personas que hoy en día no pueden valerse por sí solas, trabajar o ver la pizarra en su escuela. ¡Únete a OCULARIS!
						</p>
						<p>
							Tu aportación mensual nos permitirá seguir trabajando por un acceso a la salud visual universal e igualitaria y colaborarás en la reducción de la pobreza extrema en el África Subsahariana.
						</p>
						<p>
							¡Haz algo hoy extraordinario! Hazte socio de OCULARIS
						</p>
						<p class="content text-center">
							<?=CHtml::link(Yii::t('SocioModule.main', 'Become a associate'), array('/socio/socio/create'), array('class' => 'btn btn-primary'))?>
						</p>
						
					</div>
				</div>
				
				<div id="plugins" class="col-md-4">
					
					<div id="newsletter">
						<h3>Newsletter Ocularis</h3>
						
						<?php $form=$this->beginWidget('CActiveForm', array(
							'id'=>'newsletter-form',
							'action' => array('/mailchimp/default/index') )); ?>
						
							
							<div class="form-group">
								<?php echo CHtml::label(Yii::t('MailchimpModule.main', 'Name'), 'MailchimpForm_merge_vars_FNAME' ); ?>
								<?php echo CHtml::textField('MailchimpForm[merge_vars][FNAME]','', array('class' => 'form-control')); ?>
							</div>
							
							
							
								<div class="form-group">
									<?php echo CHtml::label('Email', 'MailchimpForm_email' ); ?>
									
										<div class="input-group">																		
											<?php echo CHtml::textField('MailchimpForm[email]','', array('class' => 'form-control')); ?>
											 <span class="input-group-btn">
												<?php echo CHtml::submitButton(Yii::t('MailchimpModule.main', "Register"), array('class' => 'btn btn-primary') ); ?>
											</span>
										</div>
									
										
											<?php echo CHtml::hiddenField("MailchimpForm[merge_vars][TIPUS]",'0'); ?>
											<?php echo CHtml::hiddenField("MailchimpForm[merge_vars][LANGUAGE]", strtoupper(Yii::app()->language) ); ?>
								
								</div>
															
							
							
							
						
						<?php $this->endWidget(); ?>

					
					</div>
					
					<div class="">
					
						<div class="facebook socialbox text-center col-sm-6 col-md-12">
							<div class="fb-like-box" data-href="https://www.facebook.com/ONG.OCULARIS" data-width="300" data-height="275" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
							<div id="fb-root"></div>
							<script>(function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&appId=293896544125555&version=v2.0";
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));</script>
						</div>
						
						<div class="twitter socialbox text-center  col-sm-6 col-md-12">
							<a class="twitter-timeline" href="https://twitter.com/ONGOCULARIS" data-widget-id="389337807291834368">Tweets por @ONGOCULARIS</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
						</div>
					
					</div>
					
				</div>
				
				</div>
			
			</div>
			
		</div>
		
		<div id="teaser">
			<div class="container">
				<?=Yii::app()->themeConfig->get('home-text')?>
			</div>
		</div>

		