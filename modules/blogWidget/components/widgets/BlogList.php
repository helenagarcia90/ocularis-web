<?php

class BlogList extends CWidget{
	
	public $anchor;
	public $limit = 5;
	public $truncate = 200;
	
	private $_dataProvider;
	private $_widget;
	
	public function init()
	{
		Yii::import('modules.blogWidget.models.BlogWidget');
		Yii::import('modules.blogWidget.BlogWidgetModule');
		
		$this->_widget = BlogWidget::model()->findByAttributes(array('anchor' => $this->anchor,
															'lang' => Yii::app()->language));
		
		if( $this->_widget === null)
			return;
		
		$this->_dataProvider = new CActiveDataProvider("BlogPage", array(
			
				'criteria' => array(
							'condition' => 'id_blog = :id_blog',
							'order' => 'published_date DESC',
							'with' => array('page' => array('scopes' => array('published'))),
							'limit' => $this->limit,
							'params' => array(
											':id_blog' => $this->_widget->id_blog,	
							),
				)
				
		));
	}
	
	public function run()
	{
		
		if( $this->_widget === null)
			return;
		
				
		$this->render("blogList",array(
							'widget' => $this->_widget,
							'blogPages' => $this->_dataProvider,
							'truncate' => $this->truncate,
		));
		
	}
	

	
}