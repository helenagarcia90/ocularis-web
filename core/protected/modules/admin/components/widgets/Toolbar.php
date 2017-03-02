<?php

/**
 * 
 * Generates a toolbar with buttons
 * Buttons a template: {saveAndClose} {saveAndStay} {saveAndNew} {close}
 * Filters: an array of model's attributes
 * 
 * @author Benoît
 *
 */

class Toolbar extends CWidget{
	
	public $buttons = array();
	
	/**
	 * Deprecated. This attribute will be ignored
	 * */
	public $filters = array();
		
	public function run()
	{
		
		$this->renderButtons();
		
		
	}
	
	private function renderButtons()
	{
		
		$output = CHtml::openTag('div', array('class' => 'pull-right'));
	
		$cs = Yii::app()->getClientScript();

		foreach($this->buttons as $button)
		{
			$handler = '';
			$button['htmlOptions'] = isset($button['htmlOptions']) ? $button['htmlOptions'] : array();
			
			$button['htmlOptions']['class'] = isset($button['htmlOptions']['class']) ? $button['htmlOptions']['class'] : '';
			
			if(strpos($button['htmlOptions']['class'],'btn-') === false)
				$button['htmlOptions']['class'] = 'btn-default';
				
			$button['htmlOptions']['class'] = $button['htmlOptions']['class'] . ' btn navbar-btn';
			
			
			$id = $button['htmlOptions']['id'] = $button['id'];
			
			if(isset($button['submit']))
			{
				$form = $button['submit'];
				$handler= '$("#'.$form.'").data("submitted",true).append("<input type=\"hidden\" name=\"action\" value=\"'.$id.'\" />");  $("#'.$form.'").submit();';

			}elseif(isset($button['link']))
			{
				$handler = 'window.location.replace("'.$button['link'].'")';
			}
			elseif(isset($button['javascript']))
			{
				$handler = $button['javascript'];
			}
			elseif(isset($button['gridView']))
			{
				
				if(!isset($button['method']) || $button['method'] === 'ajax')
				{
				
					$handler = "jQuery('#{$button['gridView']}').yiiGridView('update', {
													type: 'POST',
													url: '{$button['url']}',
													data: 'ids='+jQuery('#{$button['gridView']}').yiiGridView('getSelection'),
													success: function(data) {
														jQuery('#page-grid').yiiGridView('update');
													},
													error: function(XHR) {
														
													}
												});
												return false;
											";
				}
				elseif($button['method'] === 'POST' || $button['method'] === 'GET')
				{
					$url = isset($button['url']) ? $button['url'] : Yii::app()->request->requestUri;
					$handler = '$("<form method=\"'.$button['method'].'\" action=\"'.$url.'\"><input type=\"hidden\" name=\"action\" value=\"'.$id.'\" /><input type=\"hidden\" name=\"ids\" value=\""+jQuery("#'.$button['gridView'].'").yiiGridView("getSelection")+"\"/></form>").submit();';
					
					$handler = <<<HANDLER
					
							
					
							var \$form =  jQuery('<form method="{$button['method']}" action="{$url}"><input type="hidden" name="action" value="{$id}" /><input type="hidden" name="ids" value="'+ids.join(', ')+'"></form>');
					
					
							\$form.submit();
HANDLER;
				}
			}
			elseif(isset($button['modal']))
			{
				
				$url = $button['url'];
				
				if(strpos($url, '?') !== false)
					$url .= '&ids=';
				else
					$url .= '?ids=';
				
				$handler = <<<handler
				
				var ids = [];
					
							jQuery('#{$this->id} input[name$="_check[]"]:checked').each(function(){
									ids.push(jQuery(this).val());
							});

				if(ids.length == 0)
					return;
							
				id = encodeURIComponent(ids.join(', '));
				
				
				$('{$button['modal']}').modal({remote: '$url'+id});
				$('{$button['modal']}').on('hidden.bs.modal', function(){ $(this).removeData('bs.modal') });
						
handler;
				
			}
			
			
			
			if(isset($button['confirm']))
			{
				$handler = 'if(confirm("'.$button['confirm'].'")){' . $handler . '}else{return;}';
			}
			
			$output.= CHtml::htmlButton($button['value'],$button['htmlOptions'])."\n";
			
			$cs->registerScript('Toolbar_'.$id,'$("#'.$id.'").on("click",function(){ '. $handler .' });');
		}

		$output .= CHtml::closeTag('div');
		
		echo $output;
		
		
	}
	
	
}