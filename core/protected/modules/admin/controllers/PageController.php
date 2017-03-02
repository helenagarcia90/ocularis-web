<?php

class PageController extends AdminController
{
		
	public function actionCreateUpdate($id = null, $id_page_parent = null, $id_revision = null)
	{
		
		$this->breadcrumbs[Yii::t('page', 'CMS Pages')] = array('index');
		
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
			
			if(!Yii::app()->user->checkAccess('create'.ucfirst($this->id)))
				throw new CHttpException(403);
			
			$model = new Page();
			$model->status = Page::STATUS_UNPUBLISHED;
			$model->type = Page::TYPE_CMS;
			
			if($id_page_parent != null)
				$model->id_page_parent = $id_page_parent;
			
			$this->breadcrumbs[] = Yii::t('page', 'New Page');
			
			$pageAssocs = array();
			foreach(Lang::getList() as $lang)
			{
				$assoc = new PageAssoc();
				$pageAssocs[$lang->code] = $assoc;
			}
			
			foreach($extraOptions as $name => $extra)
			{
				$extras[$name] = new PageExtraOption();
			}

			$current = $model;
			
		}
		else
		{
			
			if(!Yii::app()->user->checkAccess('update'.ucfirst($this->id)))
				throw new CHttpException(403);
			
			$model = Page::model()->findByPk($id);
						
			if($model === null)
			{
				Yii::app()->user->setFlash("error",Yii::t('page', "The cms page does not exist"));
				$this->redirect(array('index'));
			}
			
			if(!Yii::app()->request->isPostRequest && $model->type === Page::TYPE_REVISION)
				$this->redirect( array('update', 'id' => $model->id_page_revision, 'id_revision' => $model->id_page ));
			
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
			
			$this->breadcrumbs[] = $model->title;
			
			$pageAssocs = array();
			foreach(Lang::getList() as $lang)
			{
				if(isset($model->pageAssocs[$lang->code]))
					$pageAssocs[$lang->code] = $model->pageAssocs[$lang->code];
				else
					$pageAssocs[$lang->code] = new PageAssoc();
			}
			
			foreach($extraOptions as $name => $extra)
			{
				if(isset($model->extraOptions[$name]))
					$extras[$name] = $model->extraOptions[$name];
				else
					$extras[$name] = new PageExtraOption();
			}
			
			$id_page_parent = $model->id_page_parent;
			
		}
		
		if(isset($id_page_parent))
			$this->toolbarButtons['close']['link'] = $this->createUrl('index', array('id_page' => $id_page_parent));


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
			
		$this->pageTitle = Yii::t('page', 'CMS Pages') . ": " . ($model->isNewRecord ? Yii::t('page', 'New Page') : Yii::t('page', 'Edit Page') . ": " . $model->title);
		
		if(isset($_POST['Page']))
		{
			
			$model->attributes = $_POST['Page'];
			
			if(isset($_POST['PageAssoc']))
			{
				foreach($_POST['PageAssoc'] as $lang => $id_page)
				{
					$pageAssocs[$lang]->id_page_to = $id_page;
					$pageAssocs[$lang]->lang_to = $lang;
					$pageAssocs[$lang]->lang_from = $model->lang;
				}
			}
			
			if(isset($_POST['PageExtraOption']))
			{
				foreach($_POST['PageExtraOption'] as $name => $value)
				{
					$extras[$name]->value = $value;
					$extras[$name]->name = $name;
				}
			}
			
			//only one language, take the first one
			if(count(Yii::app()->languageManager->langs)===1)
			{
				$model->lang = Yii::app()->languageManager->default;
			}
						
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
			
			$isNew = $model->isNewRecord;
			
			$model->id_author = Yii::app()->user->id_admin;
			if($model->save())
			{
				//if we save the existing page, we assign the old dependencies to the newly created revision (the new dependencies will be created later)
				if(!$isNew)
				{
					if($model->hasSavedRevision)
					{
						$revision_id = $model->getDbConnection()->getLastInsertID();
						PageAssoc::model()->updateAll(array('id_page_from' => $revision_id),"id_page_from = :id_page",array(':id_page' => $model->id_page));
						PageAssoc::model()->updateAll(array('id_page_to' => $revision_id),"id_page_to = :id_page",array(':id_page' => $model->id_page));
						PageExtraOption::model()->updateAll(array('id_page' => $revision_id),"id_page = :id_page",array(':id_page' => $model->id_page));
					}
					else
					{
						PageAssoc::model()->deleteAll("id_page_from = :id_page OR id_page_to = :id_page",array(':id_page' => $model->id_page));
						PageExtraOption::model()->deleteAll("id_page = :id_page",array(':id_page' => $model->id_page));
					}
				}
				
				foreach($pageAssocs as $assoc)
				{
				
					//no assoc for this lang
					if($assoc->id_page_to == null)
						continue;
					
					//do not save lang assoc for the page's language
					if($assoc->lang_to === $model->lang)
						continue;

					//we don't wanna set a page as its own associate either
					if($assoc->id_page_to == $model->id_page)
						continue;
										
					$assoc->id_page_from = $model->id_page;
					$assoc->isNewRecord = true; //must set this to true, otherwise update might be called though the record has been deleted: it must be reinserted
						
					//also create it's counterpart (reverse)
					$assocReverse = new PageAssoc();
					$assocReverse->id_page_to = $assoc->id_page_from;
					$assocReverse->id_page_from = $assoc->id_page_to;
					$assocReverse->lang_from = $assoc->lang_to;
					$assocReverse->lang_to = $assoc->lang_from;
					

					//Delete all pages assoc from this page's lang to the assoc page and from the assoc page to this page's lang
					PageAssoc::model()->deleteAll("(id_page_to = :id_page AND lang_from = :lang) OR (id_page_from = :id_page AND lang_to = :lang)",
								array(':id_page' => $assoc->id_page_to,
								':lang' => $model->lang));
					
					if(!$assoc->save())
						throw new CException(CVarDumper::dumpAsString($assoc->getErrors()));

					if(!$assocReverse->save())
						throw new CException(CVarDumper::dumpAsString($assocReverse->getErrors()));

				}
				
				foreach($extras as $extra)
				{
					$extra->id_page = $model->id_page;
					$extra->isNewRecord = true; //must set this to true, otherwise update might be called though the record has been deleted: it must be reinserted
					if(!$extra->save())
						throw new CException(CVarDumper::dumpAsString($extra->getErrors()));
				}
								
				if($model->lang !== null)
					SearchKeyword::index($model->content." ".$model->title, $model->lang, 'Page', $model->id_page);
				
				$t->commit();

				if($action !== 'preview' && $action !== 'auto-save')
				{
						
					unset(Yii::app()->session['autosave']);
						
					if($isNew)
						Yii::app()->user->setFlash('success',Yii::t('page', 'The page has been created successfully'));
					else
						Yii::app()->user->setFlash('success',Yii::t('page', 'The page has been updated successfully'));
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
					
					case "save-and-new":
						$this->redirect(array('create'));
					break;
					
					case "save-as-draft":
						$this->redirect(array('update','id' => $model->id_page_revision, 'id_revision' => $model->id_page ));
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
						$url = array('index');
						
						if($model->id_page_parent!=null)
							$url['id_page'] = $model->id_page_parent;
						
						$this->redirect($url);
					break;
				}
	
			}
			else
				$t->rollback();
			
		}

			
		if( ($revision = $model->unsavedRevision) !== null)
		{
			Yii::app()->user->setFlash('warning',
			Yii::t('page', 'This page has an usaved version. Click <a href="{url}">here</a> to recover it.',
			array('{url}' => $this->createUrl('update', array('id' => $model->id_page, 'id_revision' => $revision->id_page)))
			)
			);
		}
		
		
		unset(Yii::app()->session['autosave']);
		
		$this->render('form',array('model' => $model,
								'id_page_parent' => $id_page_parent,
								'pageAssocs' => $pageAssocs,
								'extras' => $extras,
								'extraOptions' => $extraOptions,
								'alternateTemplates' => $this->findAlternateTemplates(),
								'revisions' => $current->revisions,
								'current' => $current,
							));
			
	} 
	
	
	
	public function actionIndex($id_page = null, $id_page_from = null)
	{

		if(!Yii::app()->user->checkAccess('view'.ucfirst($this->id)))
			throw new CHttpException(403);
		
		$model = new Page();
		$model->unsetAttributes();
		
		//get from session
		if(isset(Yii::app()->session['PageFilters'])) {
			$model->attributes = Yii::app()->session['PageFilters'];
		}

		if(isset($_GET['Page']))
		{
			$model->attributes = Yii::app()->session['PageFilters'] = $_GET['Page'];
		}

		
		$dataProvider = new CActiveDataProvider($model);
		
		if($id_page_from !==null && ($from = Page::model()->findByPk($id_page_from) ) !== false)
		{
			$dataProvider->criteria->join = 'LEFT JOIN {{page_assoc}} pa ON (t.id_page = pa.id_page_to)';
			$dataProvider->criteria->addCondition('pa.id_page_from = :id_from');
			$dataProvider->criteria->params['id_from'] = $id_page_from;
			
			$this->breadcrumbs[Yii::t('page', 'CMS Pages')] =  array('index');
			$this->breadcrumbs[] =  $from->title;
		}
		elseif($id_page!==null)
		{
			$page = Page::model()->findByPk($id_page);
			
			$pages = array();
			$i = 0;
			$bpage = $page;
			while($bpage != null)
			{
				if($i==0)
					$pages[] = $bpage->title;
				else
					$pages[$bpage->title] = array('index','id' => $bpage->id_page);
			
				$bpage = $bpage->parent;
				$i++;
			}
			
			$this->breadcrumbs[Yii::t('page', 'CMS Pages')] =  array('index');
			$this->breadcrumbs += array_reverse($pages); 
			
			$dataProvider->criteria->addColumnCondition(array('id_page_parent' => $id_page));
		}
		else
		{
			$this->breadcrumbs[] =  Yii::t('page', 'CMS Pages');
			$dataProvider->criteria->addColumnCondition(array('id_page_parent' => null));
		}
		
		
		$dataProvider->criteria->compare('type', PAGE::TYPE_CMS);
		
		if($model->status == '')
			$dataProvider->criteria->addInCondition('status', array(Page::STATUS_PUBLISHED, Page::STATUS_UNPUBLISHED));
		else
			$dataProvider->criteria->compare('status', $model->status, false);

		if($model->id_category)
		{
			$category = Category::model()->findByPk($model->id_category);
			if($category !== null)
			{
				$dataProvider->criteria->addCondition('t.id_category IN (SELECT id_category FROM {{category}} WHERE `left` BETWEEN :left AND :right )');
				$dataProvider->criteria->params['left'] = $category->left;
				$dataProvider->criteria->params['right'] = $category->right;
			} 
		}
		
		$this->render('index',array(
				'dataProvider' => $dataProvider,
		));
	}
	
	
	public function actionTrash()
	{
	
		if(!Yii::app()->user->checkAccess('delete'.ucfirst($this->id)))
			throw new CHttpException(403);
		
		$model = new Page('search');

		if(isset($_GET['Page']))
		{
			$model->attributes = Yii::app()->session['PageFilters'] = $_GET['Page'];
		}
	
	
		$dataProvider = new CActiveDataProvider($model);
		$dataProvider->criteria->addCondition('status = :status AND ( id_page_parent IS NULL OR id_page_parent NOT IN (SELECT id_page FROM {{page}} WHERE status = :status))');
		$dataProvider->criteria->params = array('status' => Page::STATUS_TRASH);
		
		$this->breadcrumbs[Yii::t('page', 'CMS Pages')] =  array('index');
		$this->breadcrumbs[] =  Yii::t('page', 'Trash');
	
		$this->render('trash',array(
				'dataProvider' => $dataProvider,
		));
	}
	
	
	public function actionRestore()
	{
		
		if(!Yii::app()->user->checkAccess('update'.ucfirst($this->id)))
			throw new CHttpException(403);
		
		if(Yii::app()->request->getParam('ids')!==null)
			$ids = explode(', ', Yii::app()->request->getParam('ids'));
		elseif(isset($_GET['id']))
			$ids = array($_GET['id']);
		else
			$ids = array();
		
		if(isset($_POST['ids']))
		{
		
			$models = Page::model()->findAllByPk($ids);
			
			$items = array();
			
			foreach($models as $model)
			{
				$items[] = $model->id_page;
				$items = array_merge($items, $model->getAllPagesIds());
			}
	
			$ids = $items;
			
			$criteria = Page::model()->getDbCriteria();
			$criteria->addInCondition('id_page', $ids);
				
			//restaurar = estado despublicado
			$data = array('status' => Page::STATUS_UNPUBLISHED);
				
			$total = Page::model()->updateAll($data,$criteria);
			
			Yii::app()->user->setFlash('success', Yii::t('page', 'The page has been restored successfully|The pages have been restored successfully',array($total)));
			
			$this->redirect(array('trash'));
		
		}
		else
		{
			$this->layout = 'actionModal';
			$models = Page::model()->findAllByPk($ids);
			
			$criteria = new CDbCriteria();
			$criteria->addInCondition('id_page_parent', $ids);
			if(Page::model()->count($criteria)>0)
				Yii::app()->user->setFlash('warning', Yii::t('page', 'All sub pages will also be restored.'));
			
			$this->render('restore', array('models' => $models, 'ids' => implode(', ', $ids)));
		}
		
	}
	
	public function actionDelete()
	{
		
		if(!Yii::app()->user->checkAccess('delete'.ucfirst($this->id)))
			throw new CHttpException(403);

		if(Yii::app()->request->getParam('ids')!==null)
			$ids = explode(', ', Yii::app()->request->getParam('ids'));
		elseif(isset($_GET['id']))
			$ids = array($_GET['id']);
		else
			$ids = array();
		
		if(isset($_POST['ids']))
		{
			$criteria = Page::model()->getDbCriteria();
			$criteria->addInCondition('id_page', $ids);
										
			$total = Page::model()->deleteAll($criteria);
				
			Yii::app()->user->setFlash('success', Yii::t('page', 'The page has been deleted successfully|The pages have been deleted successfully',array($total)));
				
			$this->redirect(array('trash'));
		
		}
		else
		{
			$models = Page::model()->findAllByPk($ids);
			
			$criteria = new CDbCriteria();
			$criteria->addInCondition('id_page_parent', $ids);
			
			if(Page::model()->count($criteria) > 0)
				Yii::app()->user->setFlash('warning', Yii::t('page', 'All sub pages will also be deleted.'));
			
			$this->layout = 'actionModal';
			$this->render('delete', array('models' => $models, 'ids' => implode(', ', $ids)));
		}
		
	}
	
	public function actionChangeStatus()
	{
		
		if(!Yii::app()->user->checkAccess('update'.ucfirst($this->id)))
			throw new CHttpException(403);
		
		if(Yii::app()->request->getParam('ids')!==null)
			$ids = explode(', ', Yii::app()->request->getParam('ids'));
		elseif(isset($_GET['id']))
			$ids = array($_GET['id']);
		else
			$ids = array();
		
		$status = $_GET['status'];
		
		if(isset($_POST['ids']) || isset($_GET['ajax']))
		{
		
	
			if($status === Page::STATUS_TRASH)
			{
				$items = array();
				$models = Page::model()->findAllByPk($ids);
				
				foreach($models as $page)
				{
					$items[] = $page->id_page;
					$items = array_merge($items, $page->getAllPagesIds());
				}
				
				$ids = $items;
				
			}
			
			$criteria = Page::model()->getDbCriteria();
			$criteria->addInCondition('id_page', $ids);
			
			$data = array('status' => $status);
			
			if($status === Page::STATUS_PUBLISHED)
				$data['published_date'] = new CDbExpression("IF(published_date IS NULL,NOW(),published_date)");
			else
				$data['published_date'] = NULL;
			
			$total = Page::model()->updateAll($data,$criteria);
			
			if(!isset($_GET['ajax']))
			{
				switch($status)
				{
					case Page::STATUS_ARCHIVED:
						Yii::app()->user->setFlash('success', Yii::t('page', 'The page has been archived successfully|The pages have been archived successfully',array($total)));
					break;
					
					case Page::STATUS_TRASH:
						Yii::app()->user->setFlash('success', Yii::t('page', 'The page has been sent to trash|The pages have been sent to trash',array($total)));
					break;
					
					case Page::STATUS_PUBLISHED:
						Yii::app()->user->setFlash('success', Yii::t('page', 'The page has been published successfully|The pages have been published successfully',array($total)));
					break;
					
					case Page::STATUS_UNPUBLISHED:
						Yii::app()->user->setFlash('success', Yii::t('page', 'The page has been unpublished successfully|The pages have been unpublished successfully',array($total)));
					break;
					
				}
				
				$this->redirect(Yii::app()->request->urlReferrer);
			
			}
		
		}
		else
		{

			$models = Page::model()->findAllByPk($ids);
			
			if($status === Page::STATUS_TRASH)
			{
				$criteria = new CDbCriteria();
				$criteria->addInCondition('id_page_parent', $ids);
				if(Page::model()->count($criteria)>0)
					Yii::app()->user->setFlash('warning', Yii::t('page', 'All sub pages will also be sent to trash.'));
			}
			
			$this->layout = '/layouts/actionModal';
			$this->render('/page/changeStatus', array('models' => $models, 'status' => $status, 'ids' => implode(', ', $ids)));
		}
		
	}
	
	public function actionEmptyTrash()
	{
		
		if(!Yii::app()->user->checkAccess('delete'.ucfirst($this->id)))
			throw new CHttpException(403);
		
		if(Yii::app()->request->getPost('confirm'))
		{
			$criteria = Page::model()->getDbCriteria();
			$criteria->addColumnCondition(array('status' => Page::STATUS_TRASH));
			Page::model()->deleteAll($criteria);
			
			Yii::app()->user->setFlash('success',Yii::t('page', 'The trash has been emptied'));
			
			$this->redirect(array('trash'));
		}
		else
		{

			$this->renderPartial('/layouts/confirmModal', 
						array(
								'text' => Yii::t('page', 'Are you sure you want to empty the trash? All pages will be permanetly deleted'),
					)
			);
		}
	}
	
	public function actionSelector($gridId = null, $id_page = null, array $filters = array())
	{
		
		if(!Yii::app()->user->checkAccess('view'.ucfirst($this->id)))
			throw new CHttpException(403);
		
		$this->pageTitle = Yii::t('page', 'Select a page');
		
		$criteria = new CDbCriteria();
		
		foreach($filters as $column => $condition)
		{
			$criteria->compare($column, $condition);
		}
		
		if($gridId === null)
			$gridId = 'grid'.time();
		
		if($id_page !== null)
			$criteria->compare('id_page_parent', $id_page);
		else
			$criteria->addCondition('id_page_parent IS NULL');

		$criteria->addCondition('status <> :status');
		$criteria->params['status'] = Page::STATUS_TRASH;
		$criteria->addColumnCondition(array('type' => Page::TYPE_CMS));

		$dataProvider = new CActiveDataProvider('Page', array('criteria' => $criteria));
		
		//no volver a incluir jquery
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['bootstrap.min.js'] = false;
		
		$this->layout = '/layouts/modal';
		$this->render('selector',array('gridId' => $gridId, 'dataProvider' => $dataProvider));
	}

	public function actionCompare($id_v1, $id_v2)
	{
		
		if(!Yii::app()->user->checkAccess('view'.ucfirst($this->id)))
			throw new CHttpException(403);
		
		$v1 = Page::model()->findByPk($id_v1);
		$v2 = Page::model()->findByPk($id_v2);
		
		if($v1 === null || $v2 === null)
			throw CHttpException(404);
		
		$this->pageTitle = Yii::t('page', '{title1} VS. {title2}', array(
			'{title1}' => Yii::app()->locale->dateFormatter->formatDateTime($v1->update_date,'medium', 'medium'),
			'{title2}' => Yii::app()->locale->dateFormatter->formatDateTime($v2->update_date,'medium', 'medium'),
		));
		
		$pattern = '/<p[^>]*>(.*)<\/p>/U';
		
		preg_match_all($pattern, $v1->content, $matches1);
		preg_match_all($pattern, $v2->content, $matches2);
		
		$all = array();
		$index = 0;				
		$leftIndex = 0;
		
		$leftMatches = $matches1[1];
		
		for( $rightindex = 0 ; $rightindex < count($matches2[1]) ; $rightindex++)
		{

			$rightContent = $matches2[1][$rightindex];
			
			if( ($pos = array_search($rightContent, $leftMatches)) !== false )
			{
				
				foreach( $leftMatches as $k => $removed)
				{
					if($k >= $pos)
						continue;
					
					$all[$index]['content'] = $removed;
					$all[$index]['compare'] = '-';
					$index++;
					unset($leftMatches[$k]);
				}
				
				$all[$index]['content'] = $rightContent;
				$all[$index]['compare'] = '=';
				$index++;
				array_shift($leftMatches);
			}
			else
			{
				$similar = false;
				
				foreach($leftMatches as $leftKey => $leftContent)
				{
					//anything but a end of phrase char, followed by one or more end of phrase char
					$pattern = "/\s*([^\.\?\!]+[\.\?\!]*)\s*/";
					//preg_match_all($pattern,strip_tags(html_entity_decode($leftContent, ENT_COMPAT | ENT_HTML401, 'UTF-8')),$sentences1);
					preg_match_all($pattern,strip_tags($leftContent),$sentences1);
					preg_match_all($pattern,strip_tags($rightContent),$sentences2);
					
					similar_text($leftContent, $rightContent, $percent);

					//50% similarity = changed
					if( $percent > 50 )
					{
						
						for($i = 0 ; $i<$leftKey ; $i++)
						{
							if(isset($leftMatches[$i]))
							{
								$all[$index]['content'] = $leftMatches[$i];
								$all[$index]['compare'] = '-';
								$index++;
								unset($leftMatches[$i]);
							}
						}
						
						foreach($sentences2[1] as $k1 => $sentence)
						{
							if(!in_array($sentence, $sentences1[1]))
							{
								$foundSimilar = false;
								foreach($sentences1[1] as $k2 => $s)
								{
									similar_text($sentence, $s, $percent);
									
									if($percent > 50)
									{
										
										$left = preg_split('/[\s\.]+/', $s);
										$right = preg_split('/[\s\.]+/', $sentence);
										
										$onLeft = array_diff($left, $right);
										$onRight = array_diff($right, $left);
										
										foreach($onLeft as $word)
										{
											$s_new = str_replace($word, '<span class="bg-danger">'.$word.'</span>', $s);
											$leftContent = str_replace($s, $s_new, $leftContent);
										}
										
										foreach($onRight as $word)
										{
											$s_new = str_replace($word, '<span class="bg-success">'.$word.'</span>', $sentence);
											$rightContent = str_replace($sentence, $s_new, $rightContent);
										}
										
										//$leftContent = str_replace($s, '<span class="bg-info">'.$s.'</span>', $leftContent);
										//$rightContent = str_replace($sentence, '<span class="bg-info">'.$sentence.'</span>', $rightContent);
										
										unset($sentences1[1][$k2]);
										
										$foundSimilar = true;
										break;
									}
								}

								if(!$foundSimilar)
								{
									$rightContent = str_replace($sentence, '<span class="bg-success">'.$sentence.'</span>', $rightContent);
								}
							}
							
						}
						
						foreach($sentences1[1] as $sentence)
						{
							if(!in_array($sentence, $sentences2[1]))
							{
								$leftContent = str_replace($sentence, '<span class="bg-danger">'.$sentence.'</span>', $leftContent);
							}
						}
						
						$all[$index]['content'] = array( $leftContent, $rightContent );
						$all[$index]['compare'] = '~';
						$index++;
						$similar = true;
						
						unset($leftMatches[$leftKey]);
						
					}
					
				}
				
				if($similar === false)
				{
					$all[$index]['content'] = $rightContent;
					$all[$index]['compare'] = '+';
					$index++;
				}
				
			}
			
		}

		//the left element still has elements. This means they are at the end and they were removed
		
		foreach($leftMatches as $removed)
		{
			$all[$index]['content'] = $removed;
			$all[$index]['compare'] = '-';
			$index++;
		}
		
		$this->layout = '/layouts/modal';
		$this->render('/page/compare', array('v1' => $v1, 'v2' => $v2, 'all' => $all));
	}
	
	public function findAlternateTemplates()
	{
	
		if(Yii::app()->theme !== null)
			$viewPath = Yii::app()->theme->viewPath . DIRECTORY_SEPARATOR."page".DIRECTORY_SEPARATOR."templates";
		else
			$viewPath = Yii::app()->viewPath . DIRECTORY_SEPARATOR."page".DIRECTORY_SEPARATOR."templates";

		
		if(!is_dir($viewPath))
			return array();
		
		$dir = opendir($viewPath);
		
		$templates = array();
		
		while( ($file = readdir($dir)) !== false)
		{
				
			if(is_file($viewPath.DIRECTORY_SEPARATOR.$file))
			{
				$ext = substr($file, strrpos($file, ".")+1);
		
				if(strtolower($ext) === "php")
				{
					$template = substr($file,0,strrpos($file, ".php"));
					$templates[$template] = $template;
				}
			}
		}
		
		return $templates;
	}
	
	
}
	
?>