<div class="row">
	<div class="col-md-6">
		<h3 class="<?= $v1->title !== $v2->title ? 'bg-warning' : '' ?>" style="padding:10px;"><?=$v1->title?></h3>
		<p><?=Yii::app()->locale->dateFormatter->formatDateTime($v1->update_date,'medium', 'medium');?></p>
		<div>
			<?php
				foreach($all as $p)
				{
					if($p['compare'] === '+')
						continue;
					elseif($p['compare'] === '-')
						$color = 'bg-danger';
					elseif($p['compare'] === '~')
					{
						$color = 'bg-warning';
						$p['content'] = $p['content'][0];
					}
					else
						$color = '';
					
					echo '<p  style="overflow: hidden;padding:10px;" class="'.$color.'">' . $p['content'] . '</p>';
				} 
			?>
		</div>
	</div>
	
	<div class="col-md-6">
		<h3 class="<?= $v1->title !== $v2->title ? 'bg-warning' : '' ?>" style="padding:10px;"><?=$v2->title?></h3>
		<p><?=Yii::app()->locale->dateFormatter->formatDateTime($v2->update_date,'medium', 'medium');?></p>
		<div class="">
			<?php
				foreach($all as $p)
				{
					if($p['compare'] === '-')
						continue;
					elseif($p['compare'] === '+')
						$color = 'bg-success';
					elseif($p['compare'] === '~')
					{
						$color = 'bg-warning';
						$p['content'] = $p['content'][1];
					}
					else
						$color = '';
					
					echo '<p style="overflow: hidden; padding:10px;" class="'.$color.'">' . $p['content'] . '</p>';
				} 
			?>
		</div>
	</div>
</div>