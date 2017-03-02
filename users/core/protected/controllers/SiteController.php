<?php

class SiteController extends Controller
{
	
	
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', array('error' => $error) );
		}
	}

	public function actionRobots()
	{
		$this->renderPartial('robots');
	}
	
	public function actionSitemap()
	{
		header('Content-Type: text/xml');
		
		if( $this->beginCache('sitemap', array('duration' => 3600*24)) )
		{
		
				$dom = new DOMDocument('1.0', 'UTF-8');
				
				$urlset = $dom->createElement('urlset');
				$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');		
				
				$criteria = new CDbCriteria();
				$criteria->order = 'published_date DESC';
		
				//index
				$url = $dom->createElement('url');
				$loc = $dom->createElement('loc', $this->createAbsoluteUrl('/site/index'));
				$url->appendChild($loc);
				$urlset->appendChild($url);
				
				$db = Yii::app()->db;
				
				/* @var $db CDbConnection */
						
				//Pages
				$command = $db->createCommand()->select('id_page, published_date')->from('{{page}}')->where('status = :published AND type <> :revision', array('published' => Page::STATUS_PUBLISHED, 'revision' => Page::TYPE_REVISION));
				
				foreach($command->queryAll() as $page)
				{
					$url = $dom->createElement('url');
					
					$loc = $dom->createElement('loc', $this->createAbsoluteUrl('/page/index', array('id' => $page['id_page'])));
					$url->appendChild($loc);
					
					$lastmod = $dom->createElement('lastmod', CTimestamp::formatDate('Y-m-d', strtotime($page['published_date'])));
					$url->appendChild($lastmod);
					
					$urlset->appendChild($url);
				}

				//static pages
				if( Yii::app()->theme !== null )
				{
					if(is_dir( Yii::app()->theme->basePath . '/views/site/pages' ))
					{
						foreach(glob(Yii::app()->theme->basePath . '/views/site/pages/*.php') as $file)
						{
							$view = substr($file, strlen(Yii::app()->theme->basePath . '/views/site/pages/'), -4 );
							
							$url = $dom->createElement('url');
								
							$loc = $dom->createElement('loc', $this->createAbsoluteUrl('/site/page', array('view' => $view)));
							$url->appendChild($loc);
								
							$lastmod = $dom->createElement('lastmod', CTimestamp::formatDate('Y-m-d', filemtime($file)));
							$url->appendChild($lastmod);
								
							$urlset->appendChild($url);
						}
					}
					
					foreach(Yii::app()->languageManager->langs as $lang => $name)
					{
						if(is_dir( Yii::app()->theme->basePath . '/views/site/pages/'.$lang ))
						{
							foreach(glob( Yii::app()->theme->basePath . '/views/site/pages/'. $lang . '/*.php') as $file)
							{
								$view = substr($file, strlen(Yii::app()->theme->basePath . '/views/site/pages/'. $lang . '/'), -4 );
									
								$url = $dom->createElement('url');
						
								$loc = $dom->createElement('loc', $this->createAbsoluteUrl('/site/page', array('lang' => $lang, 'view' => $view)));
								$url->appendChild($loc);
						
								$lastmod = $dom->createElement('lastmod', CTimestamp::formatDate('Y-m-d', filemtime($file)));
								$url->appendChild($lastmod);
						
								$urlset->appendChild($url);
							}
						}
					}
				}
				
				//Blogs
				$command = $db->createCommand()->select('id_blog')->from('{{blog}}');
				
				foreach($command->queryColumn() as $blog)
				{
					$url = $dom->createElement('url');
						
					$loc = $dom->createElement('loc', $this->createAbsoluteUrl('/blog/index', array('id' => $blog)));
					$url->appendChild($loc);
					
						
					$urlset->appendChild($url);
				}
				
				foreach(Yii::app()->moduleManager->modulesConfig as $module)
				{
					
					foreach($module->getSitemap() as $sitemap)
					{
					
						//loc is mandatry. ignore if not defined
						if(!isset($sitemap['loc']))
							continue;
							
						$url = $dom->createElement('url');
						
						$loc = $dom->createElement('loc', $sitemap['loc']);
						$url->appendChild($loc);
							
						if(isset($sitemap['lastmod']))
						{
							$lastmod = $dom->createElement('lastmod', $sitemap['lastmod']);
							$url->appendChild($lastmod);
						}
							
						$urlset->appendChild($url);
						
					}
				}
				
				$dom->appendChild($urlset);
				echo $dom->saveXML();
		
				$this->endCache();
		}
		
	}
	
	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$success = false;
		
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			
			
			if(Yii::app()->getRequest()->getIsAjaxRequest()) {
				echo CActiveForm::validate( array( $model));
				Yii::app()->end();
			}
			
			if($model->validate())
			{
				
				$message = new YiiMailMessage();
				$message->setSubject($model->subject);
				$message->view = 'contact';
				$message->setBody(array('model' => $model),'text/html',"UTF-8");
				$message->addTo(Yii::app()->params->adminEmail);
				$message->setFrom($model->email);
				Yii::app()->mail->send($message);
				
				$success = true;
			}
		}
		$this->render('contact',array('model'=>$model, 'success' => $success));
	}
	
	public function actionLogin()
	{
		
		if(!Yii::app()->params->enableUsers)
		{
			throw new CHttpException(404);
		}
		
		$model=new LoginForm;
	
		//redirect to index if already loggedin
		if(!Yii::app()->user->isGuest)
			$this->redirect('index');
	
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionMaintenance()
	{
		$this->render('maintenance');
	}
	
}