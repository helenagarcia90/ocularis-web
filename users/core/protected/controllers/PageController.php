<?php

class PageController extends Controller
{

	public function actionIndex()
	{
		
		$id = Yii::app()->request->getParam("id");
		$alias = Yii::app()->request->getParam("alias");
		$parent = Yii::app()->request->getParam("parent");
	
    	if($id != null)	
    	{
    		if(isset($_GET['preview']) && $_GET['preview'] === '1' && !Yii::app()->userAdmin->isGuest )
				$page = Page::model()->findByPk($id);
    		else
    			$page = Page::model()->published()->findByPk($id);
    	}
    	elseif($alias !== null)
    		$page = Page::model()->published()->findByAttributes(array('alias' => $alias, 'id_page_parent' => $parent, 'lang' => Yii::app()->language));
		
		if($page === null)
			throw new CHttpException(404);

		
		$this->pageTitle = $page->getMetaTitle();
		
		if(isset($page->meta_keywords))
			Yii::app()->clientScript->registerMetaTag($page->meta_keywords,'keywords');
		if(isset($page->meta_robots))
			Yii::app()->clientScript->registerMetaTag($page->meta_robots,'robots');
		
		Yii::app()->clientScript->registerMetaTag($page->getMetaDescription(),'description');
		
		$breadCrumbs = array();
		
		switch($page->type)
		{
			case Page::TYPE_CMS:
				
				$parent = $page;
				$i=0;
				while ( ($parent = $parent->parent) !== null)
				{
					$breadCrumbs = array($parent->title => array('page/index', 'id_page' => $parent->id_page)) + $breadCrumbs;
					
					$i++;
					if($i>100)
						throw new CException("Too many loops. This might be an infinite loop. it has stopped");
				}
			
			break;
			
			case Page::TYPE_BLOG:
				$breadCrumbs[$page->blog->name] = Yii::app()->createUrl('blog/index', array('id' => $page->blog->id_blog));
			break;
		
		}
		
		$breadCrumbs[] = $page->title;

		$template = null;
		
		if($page->template !==null)
		{
			if($this->getViewFile("templates/".$page->template) !== false)
			{
				$template = "templates/".$page->template;
			}
		}
			
		if($template === null)
		{
			switch($page->type)
			{
				case Page::TYPE_BLOG:
					$template = 'post';
				break;
				case Page::TYPE_CMS:
				default:
					$template = 'index';
				break;
			}
		}

		$this->breadcrumbs = $breadCrumbs;
		
		$this->render($template,array('page' => $page,
		));
	}
	
	public function actionSearch($keywords)
	{
		
		$model = Page::model()->searchByKeywords($keywords);
		
		$criteria = new CDbCriteria(array('select' => 't.*'));
		
		$criteria->addCondition('t.type <> :revision AND t.status = :published');
		
		$criteria->params = array('revision' => Page::TYPE_REVISION, 'published' => Page::STATUS_PUBLISHED);
		
		$dataProvider = new CActiveDataProvider($model,array('criteria' => $criteria));
		
		$this->render('search',array( 'dataProvider' => $dataProvider));
		
	}
	
	public function actionBlog($id)
	{
		//for backward compatibility
		$this->forward('/blog/index');
	}

}