<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<title><?php echo CHtml::encode($this->pageTitle); ?></title>

<style>
#header
{
	margin-bottom: 50px;
}
</style>

<?php
$this->widget('LangSwitcherWidget'); 
?>
</head>

<body>
	
	<div class="container">

		<div id="header">
			<?php $this->widget('MenuWidget', array('anchor' => 'home-menu', 'id' => 'menu', 'htmlOptions' => array('class' => 'nav nav-pills pull-right') ))?>
	        <h3 class="text-muted"><?=Yii::app()->name?></h3>
      	</div>
	
		<div>
			<?=$content;?>
		</div>
		
		
	</div>
	
</body>
</html>