<?php foreach($files as $file):

$class = $file['type'] === 'folder' ? ' folder' : ' file';
$class .= $file['type'] !== 'folder' || $file['name'] !== '..' ? ' selectable' : '';
?>
<div
	class="col-xs-4 col-sm-3 col-md-2 item <?=$class?>"
	data-name="<?=$file['name']?>" data-path="<?=$file['path']?>">
	
	<div class="item-container">
		<div class="thumb">
				<?=$file['thumb']?>
		</div>

		<?php if($file['type'] !== 'folder' || $file['name'] !== '..'): ?>
		<div class="selector">
			<span class="tick glyphicon glyphicon-unchecked"></span>
		</div>
		<?php endif;?>

		<div class="icon">
			<?php if($file['type'] === 'folder'): ?>
				<span class="glyphicon glyphicon-folder-open"></span>
			<?php else:?>
				<span class="glyphicon glyphicon-file"></span>
			<?php endif;?>
			&nbsp;
		</div>
		<div class="name"><?= ($file['name'] === '..' ? '<span class="fa fa-share fa-rotate-270"></span>&nbsp;'.Yii::t('mediaManager', 'Up') : (strlen($file['name']) < 30 ? $file['name'] : substr($file['name'],0,27) . '&hellip;') ) ?></div>
		<div class="name_full">
		<?= ($file['name'] === '..' ? '<span class="fa fa-share fa-rotate-270"></span>&nbsp;'.Yii::t('mediaManager', 'Up') : $file['name'] ) ?>
		</div>
			
			<?php if( isset($file['size']) ): ?>
				<div class="details size hidden-xs"><?=$file['size']?></div>
			<?php endif;?>
	
			<?php if( isset($file['date']) ): ?>
				<div class="details date hidden-xs"><?=$file['date']?></div>
			<?php endif;?>
	
		</div>
		
	</div>
<?php endforeach; ?>
