 <?php $this->beginContent('//layouts/content');?>
 <div class="row">

<div class="col-md-8">
<?=$content?>
</div>

<div id="blog-sidebar" class="col-md-3 col-md-push-1">
				
			<h3><?=Yii::t('akashicos', 'Síguenos')?></h3>
			<?php $this->renderPartial('//includes/social');?>
</div>

</div>
<?php $this->endContent();?>