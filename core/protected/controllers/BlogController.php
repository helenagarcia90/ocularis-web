<?php

class BlogController extends Controller
{
	
	public function actionIndex($id)
	{
		$blog = Blog::model()->findByPk($id);
		
		if($blog === null)
			throw new CHttpException(404);
		
		$this->breadcrumbs[] = $blog->name;
		
		if(isset($blog->meta_keywords))
			Yii::app()->clientScript->registerMetaTag($blog->meta_keywords,'keywords');
		if(isset($blog->meta_description))
			Yii::app()->clientScript->registerMetaTag($blog->meta_description,'description');
		if(isset($blog->meta_robots))
			Yii::app()->clientScript->registerMetaTag($blog->meta_robots,'robots');
			
		if(isset($blog->meta_title))
			$this->pageTitle = $blog->meta_title;
		else
			$this->pageTitle = $blog->name;
		
		Yii::app()->clientScript->registerLinkTag('alternate', 'application/rss+xml', $this->createUrl('rss', array('id' => $id)), null, array('title' => $this->pageTitle));
		
		//get the criteria
		$criteria = new CDbCriteria();
		$criteria->addColumnCondition(array('pages.status' => Page::STATUS_PUBLISHED));
		//count total
		$count = $blog->blogPagesCount(array('join' => 'INNER JOIN {{page}} page ON (t.id_page = page.id_page)', 'condition' => 'page.status = :status', 'params' => array('status' => Page::STATUS_PUBLISHED)));
		//create the pager
		$pages=new CPagination($count);
		//apply the limit to the criteria
		$pages->applyLimit($criteria);
		//add order to criteria
		$criteria->order = 'published_date DESC';
		//fetch all posts
		$posts = $blog->pages($criteria);
		
		$this->render('index',array(
				'blog' => $blog,
				'pages' => $pages,
				'posts' => $posts,
		));
	}
	
	public function actionRss($id)
	{
		
		
		$blog = Blog::model()->findByPk($id);
		
		if($blog === null)
			throw new CHttpException(404);
		
		header('Content-Type: text/xml; charset=utf-8');
		$dom = new DOMDocument('1.0', 'UTF-8');
		
		$rss = $dom->createElement('rss');
		$rss->setAttribute('version', '2.0');
		$rss->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');
		
		$channel = $dom->createElement('channel');
		
		if(isset($blog->meta_title))
			$title = $blog->meta_title;
		else
			$title = $blog->name;
		
		$title = $dom->createElement('title', $title);
		$channel->appendChild($title);
		
		$link = $dom->createElement('link', $this->createAbsoluteUrl('index', array('id' => $id)));
		$channel->appendChild($link);
		
		$atom = $dom->createElement('atom:link');
		$atom->setAttribute('href', $this->createAbsoluteUrl('rss', array('id' => $id)));
		$atom->setAttribute('rel', 'self');
		$atom->setAttribute('type', 'application/rss+xml');
		$channel->appendChild($atom);
		
		$lang = $dom->createElement('language', $blog->lang);
		$channel->appendChild($lang);
		
		$desc = $dom->createElement('description', $blog->meta_description);
		$channel->appendChild($desc);
		
		$criteria = new CDbCriteria();
		$criteria->order = 'published_date DESC';
		$criteria->limit = 10;
		$criteria->addCondition('status = :status');
		$criteria->params = array('status' => Page::STATUS_PUBLISHED);
		
		foreach($blog->pages($criteria) as $post)
		{
			$item = $dom->createElement('item');
			
			$title = $dom->createElement('title', $post->metaTitle);
			$item->appendChild($title);
			
			if(isset($post->author->name))
			{
				$author = $dom->createElement('author', $post->author->email . "(".$post->author->name.")");
				$item->appendChild($author);
			}
						
			$link = $dom->createElement('link', $this->createAbsoluteUrl('/page/index', array('id' => $post->id_page)));
			$item->appendChild($link);
			
			$guid = $dom->createElement('guid', $this->createAbsoluteUrl('/page/index', array('id' => $post->id_page)));
			$guid->setAttribute('isPermaLink', 'true');
			$item->appendChild($guid);
			
			$date = $dom->createElement('pubDate', CTimestamp::formatDate('D, d M Y H:i:s O',strtotime($post->published_date)));
			$item->appendChild($date);
			$baseUrl = Yii::app()->getBaseUrl(true);
			$desc = preg_replace('/href="(\\/[^"]*)"/',"href=\"{$baseUrl}\$1\"",$post->excerpt);
			$desc = preg_replace('/src="(\\/[^"]*)"/',"src=\"{$baseUrl}\$1\"",$desc);
			$desc = $dom->createElement('description', CHtml::encode($desc));
			
			$item->appendChild($desc);
			
			$channel->appendChild($item);
		}
		
		$rss->appendChild($channel);
		$dom->appendChild($rss);
		
		echo $dom->saveXML();

		
	}
	
}