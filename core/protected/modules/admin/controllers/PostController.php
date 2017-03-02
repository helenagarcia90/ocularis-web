<?php

require(dirname(__FILE__).'/PageController.php');

class PostController extends PageController
{
	

	public function actionIndex($id_blog)
	{
		
		if(!Yii::app()->user->checkAccess('viewPost'))
			throw new CHttpException(403);
	
		$blog = Blog::model()->findByPk($id_blog);
	
		if($blog === null)
			throw new CHttpException(404);
	
		$model = new Page();
		$model->unsetAttributes();
	
		//get from session
		if(isset(Yii::app()->session['PageBlogFilters'])) {
			$model->attributes = Yii::app()->session['PageBlogFilters'];
		}
	
		if(isset($_GET['Page']))
		{
			$model->attributes = Yii::app()->session['PageBlogFilters'] = $_GET['Page'];
		}
	
	
		$dataProvider = new CActiveDataProvider($model);
	
		$dataProvider->criteria->join = 'LEFT JOIN {{blog_page}} bp ON (t.id_page = bp.id_page)';
		$dataProvider->criteria->addCondition('bp.id_blog = :id_blog');
		$dataProvider->criteria->params['id_blog'] = $id_blog;
	
		$this->breadcrumbs[Yii::t('blog', 'Blogs')] =  array('blog/index');
		$this->breadcrumbs[] =  $blog->name;
	
		$dataProvider->criteria->compare('type', PAGE::TYPE_BLOG);
		if($model->status == '')
			$dataProvider->criteria->addInCondition('status', array(Page::STATUS_PUBLISHED, Page::STATUS_UNPUBLISHED));
		else
			$dataProvider->criteria->compare('status', $model->status, false);
	
		$dataProvider->criteria->order = 'published_date DESC';
	
		$this->render('index',array(
				'dataProvider' => $dataProvider,
				'id_blog' => $id_blog,
		));
	}
	
	public function actionCreateUpdate($id_blog = null, $id = null, $id_revision = null)
	{
		
		$action = isset($_POST['action']) ? $_POST['action'] : 'save-and-close';
	
		$extraOptions = Yii::app()->moduleManager->getPageExtraOptions();
	
		$extras = array();
		
		if(Yii::app()->request->isPostRequest)
		{
			if(isset(Yii::app()->session['actionCreateUpdate_id_page']))
				$id = Yii::app()->session['actionCreateUpdate_id_page'];
		}
		else
			unset(Yii::app()->session['actionCreateUpdate_id_page']);
	
		if($id === null)
		{
			
			if(!Yii::app()->user->checkAccess('createPost'))
				throw new CHttpException(403);
			
			$current = $model = new Page();
			$model->status = Page::STATUS_UNPUBLISHED;
	
			$breadCrumb = Yii::t('blog', 'New Post');
	
			foreach($extraOptions as $name => $extra)
			{
				$extras[$name] = new PageExtraOption();
			}
				
			if( $id_blog === null )
				throw new CHttpException(404);
				
			if( ($blog = Blog::model()->findByPk($id_blog)) === null )
				throw new CHttpException(404);
		}
		else
		{
			if(!Yii::app()->user->checkAccess('updatePost'))
				throw new CHttpException(403);
			
			$model = Page::model()->findByPk($id);
	
			if($model === null)
			{
				throw new CHttpException(404);
			}
	
			if(!Yii::app()->request->isPostRequest && $model->type === Page::TYPE_REVISION)
				$this->redirect( array('update', 'id' => $model->id_page_revision, 'id_revision' => $model->id_page ));
				
			$blog = $model->blog;
			$current = $model;
				
			//there is a revision and this is not a post
			if($id_revision !== null && !Yii::app()->request->isPostRequest)
			{
				$revision = Page::model()->findByPk($id_revision);
			
				if($revision !== null && $revision->id_page_revision === $model->id_page)
				{
					//the revision is not a revision anymore
					$revision->status = $model->status;
					$revision->type = $model->type;
					$model = $revision;
				}
			}
		
			$breadCrumb = $model->title;
				
			foreach($extraOptions as $name => $extra)
			{
				if(isset($model->extraOptions[$name]))
					$extras[$name] = $model->extraOptions[$name];
				else
					$extras[$name] = new PageExtraOption();
			}
				
		}
	
		$this->breadcrumbs[$blog->name] = array('index', 'id_blog' => $blog->id_blog);
		$this->breadcrumbs[] = $breadCrumb;

		$this->toolbarButtons['close']['link'] = $this->createUrl('index', array('id_blog' => $blog->id_blog));
		
		$preview =  <<<EOT
		
		if( $("#Page_title", "#mainForm").val() != '' && $("#Page_content", "#mainForm").val() != '')
		{
			$("#mainForm").attr("target", "preview").append("<input type=\"hidden\" name=\"action\" value=\"preview\"/>").submit().removeAttr("target");
			$("input[name=action]", "#mainForm").remove();
		}
EOT;
		
		Yii::app()->clientScript->registerScript('previewButton','
		
				$("#Page_title, #Page_content").on("keyup change",function(){
					$("#preview").prop("disabled", $("#Page_title").val() == "" || $("#Page_content").val() == "" );
				});
		
		');
		
		if($model->status === Page::STATUS_PUBLISHED)
		{
			$this->toolbarButtons = array_merge(
					array('save-as-draft' => array(
							'id' => 'save-as-draft',
							'submit' => 'mainForm',
							'value' => '<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('toolbar', "Save as Draft"),
							'htmlOptions' => array('class' => 'btn-success'),
					))
					,$this->toolbarButtons);
		}
		
		$this->toolbarButtons = array_merge(
				array('preview' => array(
						'id' => 'preview',
						'javascript' => $preview,
						'value' => '<span class="glyphicon glyphicon-eye-open"></span> ' . Yii::t('toolbar', "Preview"),
						'htmlOptions' => array('disabled' => $model->title == '' || $model->content == '', 'class' => 'btn-success'),
		
				))
				,$this->toolbarButtons);
		
		$this->pageTitle = Yii::t('blog', 'Posts') . ": " . ($model->isNewRecord ? Yii::t('blog', 'New Post') : Yii::t('blog', 'Edit Post') . ": " . $model->title);
	
		if(isset($_POST['Page']))
		{
	
			$model->attributes = $_POST['Page'];
			$model->type = Page::TYPE_BLOG;
	
			if(isset($_POST['PageExtraOption']))
			{
				foreach($_POST['PageExtraOption'] as $name => $value)
				{
					$extras[$name]->value = $value;
					$extras[$name]->name = $name;
				}
			}
	
			$isNew = $model->isNewRecord;
	
			$t = $model->getDbConnection()->beginTransaction();
	
			if( ($action === 'preview' || $action === 'autosave' || $action === 'save-as-draft') )
			{
				$model->scenario = 'draft';
			
				if(!isset(Yii::app()->session['autosave']))
				{
					//if we preview an existing page, we do not want to save the original, instead we want to create a copy as a revision
					if(!$model->isNewRecord && $model->status === Page::STATUS_PUBLISHED)
					{
						$model->isNewRecord = true;
						$model->id_page_revision = $model->id_page;
						$model->id_page = null;
						$model->type = Page::TYPE_REVISION;
						$model->status = Page::STATUS_INHERIT;
					}
					else
						$model->status = Page::STATUS_UNPUBLISHED;
				}
				else
					$model->setSaveRevision(false);
			
			}

			//Same lang as Blog
			$model->lang = $blog->lang;
	
			if($model->validate())
			{
					
				$model->id_author = Yii::app()->user->id_admin;
				if($model->save())
				{
	
					//delete old dependencies
					if(!$isNew)
					{
						PageExtraOption::model()->deleteAll("id_page = :id_page",array(':id_page' => $model->id_page));
					}
					else
					{
						$blogPage = new BlogPage();
						$blogPage->id_page = $model->id_page;
						$blogPage->id_blog = $blog->id_blog;
						$blogPage->save();
					}			
					
	
					foreach($extras as $extra)
					{
						$extra->id_page = $model->id_page;
						$extra->isNewRecord = true; //must set this to true, otherwise update might be called though the record has been deleted: it must be reinserted
						if(!$extra->save())
							throw new CException(CVarDumper::dumpAsString($extra->getErrors()));
					}

					SearchKeyword::index($model->content." ".$model->title, $model->lang, 'Page', $model->id_page);
	
					$t->commit();
	
					if($action !== 'preview' && $action !== 'auto-save')
					{
					
						unset(Yii::app()->session['autosave']);
					
						if($isNew)
							Yii::app()->user->setFlash('success',Yii::t('blog', 'The post has been created successfully'));
						else
							Yii::app()->user->setFlash('success',Yii::t('blog', 'The post has been updated successfully'));
					}
					else
					{
						if($isNew || $model->hasSavedRevision)
							Yii::app()->session['autosave'] = true;
					
						if($isNew)
							Yii::app()->session['actionCreateUpdate_id_page'] = $model->id_page;
					}
	
					switch($action)
					{
						case 'auto-save':
							Yii::app()->end();
							break;
								
						case "preview":
							$this->redirect(array('/page/index', 'id' => $model->id_page, 'preview' => true));
						break;

						case "save-as-draft":
							$this->redirect(array('update','id' => $model->id_page_revision, 'id_revision' => $model->id_page ));
						break;
						
						case "save-and-new":
							$this->redirect(array('create', 'id_blog' => $blog->id_blog));
							break;
	
						case "save-and-stay":
							if($model->status === Page::TYPE_REVISION)
								$id = $model->id_page_revision;
							else
								$id = $model->id_page;
							
							$this->redirect(array('update','id' => $id));
						break;
	
						case "save-and-close":
						default:
							$this->redirect(array('index', 'id_blog' => $blog->id_blog, 'id_blog' => $blog->id_blog));
							break;
					}
	
				}
				else
					$t->rollback();
			}
		}
	
		if( ($revision = $model->unsavedRevision) !== null)
		{
			Yii::app()->user->setFlash('warning',
			Yii::t('blog', 'This post has an usaved version. Click <a href="{url}">here</a> to recover it.',
			array('{url}' => $this->createUrl('update', array('id' => $model->id_page, 'id_revision' => $revision->id_page)))
			)
			);
		}
		

		unset(Yii::app()->session['autosave']);
		
		$this->render('form',array('model' => $model,
				'extras' => $extras,
				'extraOptions' => $extraOptions,
				'alternateTemplates' => $this->findAlternateTemplates(),
				'revisions' => $current->revisions,
				'current' => $current,
	
		));
			
	}
	
	
}