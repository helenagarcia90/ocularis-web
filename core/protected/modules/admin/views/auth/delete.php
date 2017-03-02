<p>
<?=Yii::t('auth', "Are you sure you want to delete the role <strong>{role}</strong>?", array('{role}' => $role)); ?>
</p>
<?= CHtml::hiddenField('role',$role);?>
