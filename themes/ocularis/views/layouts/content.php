
<?php /*  @var $this  Controller */ ?>

<?php $this->beginContent('//layouts/main');?>

	<?php $this->widget('modules.slider.components.widgets.SliderWidget', array('anchor' => 'home')); ?>
		
	<div id="page">

		<div id="content">
			<?= $content ?>
		</div>
	</div>
	
<?php $this->endContent()?>
