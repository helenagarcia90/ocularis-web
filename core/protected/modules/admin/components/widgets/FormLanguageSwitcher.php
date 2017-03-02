<?php


class FormLanguageSwitcher extends CWidget
{
	

	public function run()
	{


		Yii::app()->clientScript->registerSCript('lang_switcher', '
		
		
			$("body").on("click",".lang_switcher", function(){
		
				$(".lang_switch").hide();
				$(".lang_switcher_selected").html($(this).html());
				$(".lang_switch[-data-lang="+$(this).attr("-data-lang")+"]").show();
				$(".form_language_switcher_float").hide();
		
			});
				
			$("body").on("mouseenter",".form_language_switcher", function(){
	
						$(this).find(".form_language_switcher_float").fadeIn("fast");
				
				});
				
			$("body").on("mouseleave",".form_language_switcher", function(){
	
		
						$(this).find(".form_language_switcher_float").fadeOut("fast");
				});
		
			');
		
		Yii::app()->clientScript->registerCss('lang_switcher', '
				
				
				.form_language_switcher
				{
					position: relative;
					display: inline-block;
					bottom: 10px;
					vertical-align: top;
					margin-top: 10px;
				}
				
				.form_language_switcher_float
				{
					display:none;
					position: absolute;
					left: 0;
					top: 0;
					border: 1px solid #e1e1e1;
					border-radius: 4px;
					padding: 5px;
					box-shadow: 0px 0px 2px 0px rgba(0,0,0,.5);
					background: #fff;
					width: 74px;
				}
				
				
				');
		
		
						echo CHtml::openTag('div', array('class' => 'form_language_switcher') );
				
						echo '<span class="lang_switcher_selected">'.AdminHelper::flag(Lang::model()->findByAttributes( array('code' => Yii::app()->language) )).'</span> ';
				
						
						echo CHtml::openTag('div', array('class' => 'form_language_switcher_float'));
						foreach(Yii::app()->params->langs as $code => $lang)
						{
							$language = Lang::model()->findByAttributes( array('code' => $code) );
							echo '<span class="lang_switcher" -data-lang="'.$code.'">'.AdminHelper::flag($language).'</span> ';
						}
				echo CHtml::closeTag('div');
				
			echo CHtml::closeTag('div');
	}
	
	
	
}