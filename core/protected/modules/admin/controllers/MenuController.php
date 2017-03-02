<?php

class MenuController extends AdminController
{
	
	public function actionIndex()
	{
		
		if(!Yii::app()->user->checkAccess('viewMenu'))
			throw new CHttpException(403);
		
		$this->breadcrumbs[] = Yii::t('menu', 'Menus');

		$this->render('index',array(
					'dataProvider' => new CActiveDataProvider('Menu'),
		));
	}

		
	public function actionCreateUpdate($id = null)
	{
		
		$this->breadcrumbs[Yii::t('menu', 'Menus')] = array('index');
		
		$action = isset($_POST['action']) ? $_POST['action'] : 'save-and-close';
		
		if($id === null)
		{
			if(!Yii::app()->user->checkAccess('createMenu'))
				throw new CHttpException(403);
			
			$model = new Menu();
			$this->breadcrumbs[] = Yii::t('menu', 'New Menu'); 
		}
		else
		{
			if(!Yii::app()->user->checkAccess('updateMenu'))
				throw new CHttpException(403);
			
			$model = Menu::model()->findByPk($id);
			
			if($model === null)
			{
				Yii::app()->user->setFlash("error",Yii::t('menu', "The menu does not exist"));
				$this->redirect(array('index'));
			}
			
			$this->breadcrumbs[] = $model->name;
			
		}
		
		$this->pageTitle = Yii::t('menu', 'Menus') . ": " . ($model->isNewRecord ? Yii::t('menu', 'New Menu') : Yii::t('menu', 'Edit Menu') . ': ' . $model->name );
		
		if(isset($_POST['Menu']))
		{
		
			$model->attributes = $_POST['Menu'];
					
			//only one language, take the first one

			if(count(Yii::app()->languageManager->langs)===1)
			{
				$model->lang = Yii::app()->languageManager->default;
			}
			
			
			
			$isNew = $model->isNewRecord;
			
			if($model->save())
			{
				
				if($isNew)
					Yii::app()->user->setFlash('success',Yii::t('menu', 'The menu has been created successfully'));
				else
					Yii::app()->user->setFlash('success',Yii::t('menu', 'The menu has been updated successfully'));
			
				//invalidate cache
				Yii::app()->cache->delete("MenuWidget.{$model->anchor}.{$model->lang}");
				
				switch($action)
				{
					case "save-and-new":
						$this->redirect(array('create'));
						break;
					
					case "save-and-stay":
						if($isNew)
							$this->redirect(array('update','id' => $model->id_menu));
					break;
					
					case "save-and-close":
					default:
						$this->redirect(array('index'));
					break;
				}

			}

		}
		
			
		$this->render('form',array('model' => $model));
			
	} 
	
	public function actionDelete()
	{
	
		if(!Yii::app()->user->checkAccess('deleteMenu'))
			throw new CHttpException(403);
		
		if(Yii::app()->request->getParam('ids')!==null)
			$ids = explode(', ', Yii::app()->request->getParam('ids'));
		elseif(isset($_GET['id']))
			$ids = array($_GET['id']);
		else
			$ids = array();
		
		if(isset($_POST['ids']))
		{
			
			$criteria=Menu::model()->getDbCriteria();
			$criteria->addInCondition('id_menu', $ids);
		
			$total = Menu::model()->deleteAll($criteria);
		
			//invalidate cache
			Yii::app()->cache->delete("MenuWidget.{$model->anchor}.{$model->lang}");
			
			Yii::app()->user->setFlash('success',Yii::t('menu', 'The menu has been deleted successfully|The menus have been deleted successfully',array($total)));
	
			$this->redirect(array('index'));
		}
		else
		{
			$models = Menu::model()->findAllByPk($ids);
			
			$criteria = new CDbCriteria();
			$criteria->addInCondition('id_menu', $ids);
			
			if(MenuItem::model()->count($criteria) > 0)
			       	Yii::app()->user->setFlash('warning', Yii::t('menu', 'The menu items will also be deleted.') );
			
			$this->layout = '/layouts/actionModal';
			$this->render('delete', array('models' => $models, 'ids' => implode(', ',$ids)));
		}
				
	}
	
}