<?php
foreach($roles as $name => $role): ?>

<div class="form-group group">

	<div class="checkbox">
		<label>
		  <input type="checkbox" name="roles[]" <?=!Yii::app()->user->checkAccess($name) ? 'disabled="disabled"' : ''?>  value="<?=$name?>" <?= in_array($name, $adminRoles) ? 'checked="checked"' : '' ?>/> <?=$name?>
		</label>
	</div>

</div>

<?php endforeach;?>