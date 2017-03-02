<?php

class LastPages extends CWidget{

	
	public $type = Page::TYPE_CMS;
	public $limit = 10;
	public $date_attribute = "update_date";
	public $title = null;
	
	public function init(){
		
		
		
	}
	
	public function run()
	{
		
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('type' => $this->type));
		$criteria->order = $this->date_attribute . ' DESC';

		$provider = new CActiveDataProvider(Page::model(),array('criteria' => $criteria));
		
		$this->render('lastPages',array('provider' => $provider, 'pageSize' => $this->limit, 'title' => $this->title));
		
	}
	
	
}
