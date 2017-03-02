<div class="alert alert-warning">
<h1><span class="label label-danger"><?=$error['code']?></span></h1>
<br/>
<p>
<?=Yii::t('app', 'This page does not exist. Please check the url or report back the error to us.');?>
</p>
<br/>
<p>
<?= CHtml::link('<span class="glyphicon glyphicon-dashboard"></span>&nbsp;' . Yii::t('app', 'Go to the dashboard'), array('site/index'), array('class' => 'btn btn-primary'))?></li>
<?= CHtml::link('<span class="fa fa-bug"></span>&nbsp;' . Yii::t('app', 'Report a bug'), 'https://bitbucket.org/bbwebconsult/biicms/issues/new', array('class' => 'btn btn-primary', 'target' => '_blank'))?></li>
</div>
</p>

