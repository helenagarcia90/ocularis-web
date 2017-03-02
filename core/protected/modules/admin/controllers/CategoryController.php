<?php

class CategoryController extends AdminController
{

	
	public function actionCreateUpdate($id = null, $id_category = null)
	{
		
		$this->breadcrumbs[Yii::t('page', 'Categories')] = array('index');
		
		$action = isset($_POST['action']) ? $_POST['action'] : 'save-and-close';
		
		if($id === null)
		{
			
			if(!Yii::app()->user->checkAccess('createCategory'))
				throw new CHttpException(403);
			
			$model = new Category();
			if($id_category!=null)
				$model->id_category_parent = $id_category;
			
			$this->breadcrumbs[] = Yii::t('page', 'New Category');
		}
		else
		{
			
			if(!Yii::app()->user->checkAccess('updateCategory'))
				throw new CHttpException(403);
			
			$model = Category::model()->findByPk($id);
			
			if($model === null)
			{
				Yii::app()->user->setFlash("error",Yii::t('page', "The category does not exist"));
				$this->redirect(array('index'));
			}
			
			$this->breadcrumbs[] = $model->name;
		}
		
		if(isset($_POST['Category']))
		{
		
			$model->attributes = $_POST['Category'];
				
			
			$isNew = $model->isNewRecord;
			
			$transaction = $model->getDbConnection()->beginTransaction();
			
			if($model->save())
			{
				
				Category::rebuildHierarchy();
				
				$transaction->commit();
				
				if($isNew)
					Yii::app()->user->setFlash('success',Yii::t('page', 'The category has been created successfully'));
				else
					Yii::app()->user->setFlash('success',Yii::t('page', 'The category has been modified successfully'));
				
				switch($action)
				{
					case "save-and-new":
						$this->redirect(array('create'));
						break;
					
					case "save-and-stay":
						if($isNew)
							$this->redirect(array('update','id' => $model->id_category));
					break;
					
					case "save-and-close":
					default:
						$url = array('index');
						
						if($model->id_category_parent!=null)
							$url['id'] = $model->id_category_parent;
						
						$this->redirect($url);						
					break;
				}

			}
			else
			{
				$transaction->rollback();
			}

		}
			
		$this->render('form',array('model' => $model));
			
	} 
	
	
	
	public function actionIndex($id_category = null)
	{

		if(!Yii::app()->user->checkAccess('viewCategory'))
			throw new CHttpException(403);
		
		$this->breadcrumbs[] = Yii::t('page', 'Categories');
	
		$dataProvider = new CActiveDataProvider(Category::model());
		
		if($id_category!==null)
		{
			$category = Category::model()->findByPk($id_category);

			if($category === null)
				throw new CHttpException(404);
			
			$dataProvider->criteria->addColumnCondition(array('id_category_parent' => $id_category));
			
			$categories = array();
			$bcategory = $category;
			$i = 0;
			while($bcategory != null)
			{
				if($i==0)
					$categories[] = $bcategory->name;
				else
					$categories[$bcategory->name] = array('index','id' => $bcategory->id_category);
			
				$bcategory = $bcategory->parent;
				$i++;
			}
			
			array_pop($this->breadcrumbs); //remove last element
			$this->breadcrumbs[Yii::t('page', 'Categories')] = array('index');
			 
			$categories = array_reverse($categories);
						
			$this->breadcrumbs += $categories;
		}
		else
			$dataProvider->criteria->addCondition('id_category_parent IS NULL');
		
		$this->render('index',array(
				'dataProvider' => $dataProvider,
				
		));
	}

	
	public function actionDelete()
	{
		
		if(!Yii::app()->user->checkAccess('deleteCategory'))
			throw new CHttpException(403);
		
		if(Yii::app()->request->getParam('ids')!==null)
			$ids = explode(', ', Yii::app()->request->getParam('ids'));
		elseif(isset($_GET['id']))
			$ids = array($_GET['id']);
		else
			$ids = array();
		
		
		if(isset($_POST['ids']))
		{
		
				$criteria=Category::model()->getDbCriteria();
				$criteria->addInCondition('id_category', $ids);
				
				$total = Category::model()->deleteAll($criteria);
					
				Yii::app()->user->setFlash('success',Yii::t('page', 'The category has been deleted successfully|The categories have been deleted successfully',array($total)));
				$this->redirect(array('index'));
		
		}
		else
		{
			$models = Category::model()->findAllByPk($ids);
			
			$count_p = $count_c = 0;
			
			foreach($models as $model)
			{
				$count_c += Category::model()->count('`left` > :left AND `right` < :right', array('left' => $model->left, 'right' => $model->right));
				$count_p += Page::model()->count('`id_category` IN (SELECT id_category FROM {{category}} WHERE `left` >= :left AND `right` <= :right)', array('left' => $model->left, 'right' => $model->right));
			}
			
			$warnings = array();
			
			if($count_c > 0)
				$warnings[] = Yii::t('page', 'The sub categories will also be deleted.');
			
			if($count_p > 0)
				$warnings[] = Yii::t('page', 'The pages will be unassigned from the deleted category.|The pages will be unassigned from the deleted categories.', count($ids));
			
			if(count($warnings)>0)
				Yii::app()->user->setFlash('warning', $warnings);
			
			$this->layout = '/layouts/actionModal';	
			$this->render('delete', array('models' => $models, 'ids' => implode(', ', $ids)));
		}
		
	}

}
	
?>