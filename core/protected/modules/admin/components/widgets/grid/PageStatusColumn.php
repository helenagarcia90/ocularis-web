<?php
class PageStatusColumn extends DataColumn {
	
	
	public $filter = false;
	
	function init()
	{
		parent::init();
	
		if(!isset($this->headerHtmlOptions['class']))
			$this->headerHtmlOptions['class'] = 'col-sm text-center';
		else
			$this->headerHtmlOptions['class'] .= ' col-sm text-center';
		
		if(!isset($this->htmlOptions['class']))
			$this->htmlOptions['class'] = 'text-center';
		else
			$this->htmlOptions['class'] .= ' text-center';
			
	}
	
	protected function renderDataCellContent($row, $data) {
		
		$state = $tip = '';
		
		if ($data->status === Page::STATUS_PUBLISHED) {
			$state = 'published active';
			$tip = Yii::t('page', 'Published');
		} elseif ($data->status === Page::STATUS_UNPUBLISHED) {
			$state = 'unpublished';
			$tip = Yii::t('page', 'Unpublished');
		} elseif ($data->status === Page::STATUS_ARCHIVED) {
			$state = 'archived';
			$tip = Yii::t('page', 'Archived');
		}
		
		echo CHtml::link ( '', array (
				'changeStatus',
				'id' => $data->id_page 
		), array (
				'class' => 'btn btn-default btn-xs state_switch tooltipped ' . $state,
				'title' => $tip,
		) );
		
		Yii::app ()->clientScript->registerScript ( __CLASS__, "
				
				
				$('#{$this->grid->id}').on('click', '.state_switch',function(e){
	
						e.preventDefault();
						
						$('#{$this->grid->id}').modelGridView('showLoad');
						
						var status;
						var btn = $(this);
						
						if($(this).hasClass('published'))
						{
							status = " . CJavaScript::encode ( Page::STATUS_UNPUBLISHED ) . ";
							btn.removeClass('active');
						}
						else
						{
							status = " . CJavaScript::encode ( Page::STATUS_PUBLISHED ) . ";
							btn.addClass('active');
						}
						
									
						" . CHtml::ajax ( array (
									'url' => 'js:$(this).attr("href")',
									'data' => "js:'ajax=1&status='+status",
									'success' => "js:function(result){
						
														if(btn.hasClass('published'))
														{
															btn.removeClass('published').addClass('unpublished');
															btn.attr('title','".Yii::t('page','Unpublished')."').attr('data-original-title','".Yii::t('page','Unpublished')."');
														}
														else
														{
															btn.removeClass('unpublished').addClass('published');
															btn.attr('title','".Yii::t('page','Published')."').attr('data-original-title','".Yii::t('page','Published')."');
														}
								
														if(btn.hasClass('archived'))
															btn.removeClass('archived');
					
														$('#{$this->grid->id}').modelGridView('hideLoad');
													
													}" 
							) ) . "
						
				});
				
		" );
	}
}