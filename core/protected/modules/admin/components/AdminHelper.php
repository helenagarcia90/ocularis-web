<?php

class AdminHelper
{
	
	public static function flag($language)
	{
		return CHtml::image(Yii::app()->getModule('admin')->assets ."/images/lang/".$language->code.".jpg",$language->name, array("title" => $language->name));
	}
	
}