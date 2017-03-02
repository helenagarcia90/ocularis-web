
<?php 



Yii::app()->clientScript->registerScript('ajax','
		
		$("body").on("change","#MenuItem_id_menu",function(){

		'.
		CHtml::ajax(array(
						'url' => $this->createUrl('menuItem/findItems'),
						'data' => 'js:"id_menu="+$("#MenuItem_id_menu").val()+"&id_menu_item_parent='.$model->id_menu_item.'"',
						'type' => 'GET',
						'dataType' => 'json',
						'success' => 'function(data){
								
									$("#MenuItem_id_menu_item_parent").val("");
									$("#MenuItem_id_menu_item_parent_dropdown button .value").html("'.Yii::t('menu', 'Parent Item').'");
									$("#MenuItem_id_menu_item_parent_dropdown ul").html("<li class=\"option dropdown-header\" data-value=\"\">'.Yii::t('menu', 'Parent Item').'</li><li class=\"divider\"></li>");

									for(var key in data ){
										$("#MenuItem_id_menu_item_parent_dropdown ul").append("<li class=\"option\" data-value=\""+data[key]["id"]+"\"><span>"+data[key]["label"]+"</span></li>");
							        }
											
							 }',
						))
		.'
			});
		
		$("body").on("change","#MenuItem_id_menu, #MenuItem_id_menu_item_parent",function(){

		'.
		CHtml::ajax(array(
						'url' => $this->createUrl('menuItem/findPositions'),
						'data' => 'js:"id_menu="+$("#MenuItem_id_menu").val()+"&id_menu_item_parent="+$("#MenuItem_id_menu_item_parent").val()+"&id_menu_item='.$model->id_menu_item.'"',
						'type' => 'GET',
						'dataType' => 'json',
						'success' => 'function(data){
								
									$("#MenuItem_position").val("");
									$("#MenuItem_position_dropdown button .value").html("'.Yii::t('menu', 'Position (after...)').'");
									$("#MenuItem_position_dropdown ul").html("<li class=\"option dropdown-header\" data-value=\"\">'.Yii::t('menu', 'Position (after...)').'</li><li class=\"divider\"></li>");
								
									$.each(data, function(k, v) {
									 $("#MenuItem_position_dropdown ul").append("<li class=\"option\" data-value=\""+k+"\"><span>"+v+"</span></li>");   
									}); 
							 }',
						))
		.'
			});
								
								
		
		');


	$form->fieldGroup('textField', $model, 'label');

	$form->fieldGroup('dropDownList', $model, 'id_menu',  array('data' =>  Menu::getListData()) );
	
	$form->fieldGroup('dropDownList', $model, 'id_menu_item_parent',  array('data' =>  MenuItem::getListData($model->id_menu,$model->id_menu_item)) );
	
	$form->fieldGroup('dropDownList', $model, 'position', array('data' => MenuItem::getPositionListData($model->id_menu,$model->id_menu_item_parent,$model->id_menu_item)) );

	$form->fieldGroup('pageSelector', $model, 'link', array('scope' => 'menuitem') );
	
	$form->fieldGroup('dropDownList', $model, 'target', array( 'data' => MenuItem::getTargetListData()) );
	
	$form->fieldGroup('switcher', $model, 'active',  array('items' =>  array(
	
			array('value' => 1,
					'label' => Yii::t('app', 'Enabled'),
					'color' => 'btn-success'),
	
			array('value' => 0,
					'label' => Yii::t('app', 'Disabled'),
					'color' => 'btn-danger'),
	
	)));
?>

