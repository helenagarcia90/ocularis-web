<div class="alert alert-danger">
<h1><span class="glyphicon glyphicon-minus-sign"></span>&nbsp;<?=Yii::t('app', 'Unauthorized access')?></h1>
<br/>
<p>
<?=Yii::t('app', 'You are not authorized to access this page.') ?>
</p>
<br/>
<p>
<?= CHtml::link('<span class="glyphicon glyphicon-dashboard"></span>&nbsp;' . Yii::t('app', 'Go to the dashboard'), array('site/index'), array('class' => 'btn btn-primary'))?></li>
</div>
</p>

