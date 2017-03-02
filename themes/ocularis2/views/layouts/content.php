<?php $this->beginContent('//layouts/main'); ?>
				
<div id="main-container" class="container">
<?php if(isset($this->breadcrumbs)):?>
					<?php $this->widget('zii.widgets.CBreadcrumbs', array(
						'links'=>$this->breadcrumbs,
						'homeLink' => CHtml::tag('li',array(),CHtml::link(Yii::t('app','Home'),array('/site/index'))),
						'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
						'inactiveLinkTemplate' => '<li class="active">{label}</li>',
						'tagName' => 'ul',
						'htmlOptions' => array('class' => 'breadcrumb'),
						'separator' => '',
					)); ?><!-- breadcrumbs -->
<?php endif?>

	<?=$content?>
</div>
<?php $this->endContent();?>