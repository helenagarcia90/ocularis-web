<style>

#imageBrowser
{
	overflow: hidden;
	font-size: 12px;
}

#imageBrowser .image-block
{
	width: 160px;
	height: 220px;
	border: 2px solid #ABEBFF;
	text-align: center;
	padding: 5px;
	margin: 5px;
	float: left;
	word-wrap: break-word;
}


#imageBrowser .image-block .image
{
	width: 150px;
	height: 150px;
	margin: auto;
	margin-bottom:  10px;
	
}

#imageBrowser .image-block img
{
	max-width: 150px;max-height: 150px;
}

#imageBrowser .image-block .name a
{
	font-weight: bold;
	color: #000;
}

</style>

<script lang="text/javascript">

	function selectImage(url)
	{
		window.opener.CKEDITOR.tools.callFunction(<?php echo $callback; ?>,"<?=$url?>/"+url);
		window.close();
	}

</script>

<div id="imageBrowser">

<?php 

foreach($images as $image):?>

	<div class="image-block">
		<div class="image">
			<img src="<?=$basePath."/".$image['name'];?>" alt="<?=$image['name'];?>"/><br/>
		</div>
		<span class="name"><?=CHtml::link($image['name'],"javascript:selectImage('{$image['name']}');")?></span><br/>
		<span class="details"><?=$image['date'];?> - <?=$image['size'];?></span><br/>
	</div>

<?php endforeach;?>

</div>