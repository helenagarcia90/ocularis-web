<?php /* @var homeBlocks $items  */ ?>

<script type="text/javascript">
					/*<![CDATA[*/
					    jQuery(function($) {
					
							
							$('.box').hover(function(){
									
								$(this).find("img.normal").show();
								$(this).find("img.alternate").hide();
									
								
							},
								function(){
								
								$(this).find("img.alternate").show();
								$(this).find("img.normal").hide();
								
								
							}	);
						
				
							
					});
					/*]]>*/
				</script>
<div id="boxes">
<?php 
require Yii::app()->theme->basePath . '/components/HomeBlocskImageFilter.php';
foreach($items as $item):
?>
			<div class="box">
				<div class="content">
					<img class="normal" style="display: none;"  src="<?=Yii::app()->imageCache->createUrl('home-block', $item->image);?>" alt="<?=$item->title?>" />
					<img class="alternate" src="<?=HomeBlocskImageFilter::filter(Yii::app()->imageCache->createUrl('home-block', $item->image));?>" alt="<?=$item->title?>" />
					
					<h2><?=$item->title?></h2>
					<?=$item->content?>
					<?=CHtml::link(Yii::t('HomeBlocksModule.main', 'Read more'), Helper::parseLink($item->link), array('class' => 'link'));?>
				</div>
			</div>
<?php endforeach;?>
</div>