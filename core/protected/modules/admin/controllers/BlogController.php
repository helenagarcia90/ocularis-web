<?php

class BlogController extends AdminController
{

	
	public function actionCreateUpdate($id = null)
	{

		$this->breadcrumbs[Yii::t('blog', 'Blogs')] =  array('index');
		
		$action = isset($_POST['action']) ? $_POST['action'] : 'save-and-close';
		
		if($id === null)
		{
			
			if(!Yii::app()->user->checkAccess('createBlog'))
				throw new CHttpException(403);
			
			$model = new Blog();
			$this->breadcrumbs[] = Yii::t('blog', 'New Blog');
		}
		else
		{
			
			if(!Yii::app()->user->checkAccess('updateBlog'))
				throw new CHttpException(403);
			
			$model = Blog::model()->findByPk($id);
			
			if($model === null)
			{
				Yii::app()->user->setFlash("error",Yii::t('blog', "The blog does not exist"));
				$this->redirect(array('index'));
			}
			
			$this->breadcrumbs[] = $model->name;

		}

		if(isset($_POST['Blog']))
		{
		
			$model->attributes = $_POST['Blog'];
			
			//only one language, take the default
			if(count(Yii::app()->languageManager->langs)===1)
			{
				$model->lang = Yii::app()->languageManager->default;
			}
			
			$isNew = $model->isNewRecord;
			
			if($model->save())
			{
				if($isNew)
					Yii::app()->user->setFlash('success',Yii::t('blog', 'The blog has been created successfully'));
				else
					Yii::app()->user->setFlash('success',Yii::t('blog', 'The blog has been modified successfully'));
				
				switch($action)
				{
					case "save-and-new":
						$this->redirect(array('create'));
						break;
					
					case "save-and-stay":
						if($isNew)
							$this->redirect(array('update','id' => $model->id_blog));
					break;
					
					case "save-and-close":
					default:
						$this->redirect( array('index'));						
					break;
				}

			}

		}
			
		$this->render('form',array('model' => $model));
			
	} 
	
	
	
	public function actionIndex()
	{
		
		if(!Yii::app()->user->checkAccess('viewBlog'))
			throw new CHttpException(403);
		
		$this->breadcrumbs[] =  Yii::t('blog', 'Blogs');
		
		$dataProvider = new CActiveDataProvider(Blog::model());

		$this->render('index',array(
				'dataProvider' => $dataProvider,
				
		));
	}
	
	public function actionDelete()
	{
		
		if(!Yii::app()->user->checkAccess('deleteBlog'))
			throw new CHttpException(403);
		
		if(Yii::app()->request->getParam('ids')!==null)
			$ids = explode(', ', Yii::app()->request->getParam('ids'));
		elseif(isset($_GET['id']))
			$ids = array($_GET['id']);
		else
			$ids = array();
		
		if(isset($_POST['ids']))
		{
				$transaction = Blog::model()->getDbConnection()->beginTransaction();
				
				$criteria=Blog::model()->getDbCriteria();
				$criteria->addInCondition('id_blog', $ids);

				//delete pages
				$params = array();
				
				foreach($ids as $key => $id)
				{
					$params[':id'.$key] = $id;
				}
				
				Page::model()->deleteAll('id_page IN (SELECT id_page FROM {{blog_page}} WHERE id_blog IN ('.implode(', ',array_keys($params)).'))', $params);
				
				$total = Blog::model()->deleteAll($criteria);

				$transaction->commit();
				
				Yii::app()->user->setFlash('success',Yii::t('blog', 'The blog has been deleted successfully|The blogs have been deleted successfully',array($total)));
				$this->redirect(array('index'));
		
		}
		else
		{
			$models = Blog::model()->findAllByPk($ids);
			$this->layout = '/layouts/actionModal';
			
			$criteria = new CDbCriteria();
			$criteria->addInCondition('id_blog', $ids);
			
			if(BlogPage::model()->count($criteria)>0)
				Yii::app()->user->setFlash('warning',Yii::t('blog','The posts will also be deleted.'));
			
			$this->render('delete', array('models' => $models, 'ids' => implode(', ',$ids)));
		}
		
	}

}
	
?>