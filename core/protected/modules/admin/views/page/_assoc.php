

<div id="langAssoc">
<?php foreach(Lang::getList() as $key => $lang): ?>
	<div class="form-group" id="langAssocRow_<?=$lang->code;?>" <?= $model->lang==$lang->code ? 'style="display: none;"' : ''; ?>>
		<?php
	
			$this->widget('Selector',
				array(	
						'name'		 	=> "PageAssoc[$lang->code]",
						'value' => CHtml::value($pageAssocs[$lang->code], "id_page_to",''),
						'displayValue'	=> CHtml::value($pageAssocs[$lang->code], "pageTo.title",''),
						'placeHolder' => Yii::t('page', 'Please, select a page'),
						'url' 	=> $this->createUrl('selector',array('filters' => array('lang' => $lang->code))),
						'options' => array('label' => $lang->name,),
					));
		?>
	</div>
	<?php endforeach;?>
</div>