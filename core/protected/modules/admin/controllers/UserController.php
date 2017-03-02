<?php

class UserController extends AdminController
{
	
	public function actionIndex()
	{

		if(!Yii::app()->user->checkAccess('viewUser'))
			throw new CHttpException(403);
		
		$this->breadcrumbs[] =  Yii::t('user', 'Users');
		$this->pageTitle = Yii::t('user', 'Users');
				
		$dataProvider = new CActiveDataProvider(User::model());
			
		$this->render('index',array(
				'dataProvider' => $dataProvider,
		));
	}
		
	public function actionCreateUpdate($id = null)
	{
		
		$this->breadcrumbs[Yii::t('user', 'Users')] =  array('index');
		
		$action = isset($_POST['action']) ? $_POST['action'] : 'save-and-close';
		
		if($id === null)
		{
			if(!Yii::app()->user->checkAccess('createUser'))
				throw new CHttpException(403);
			
			$model = new User();
			$this->breadcrumbs[] = Yii::t('user','New User');
		}
		else
		{
			
			if(!Yii::app()->user->checkAccess('updateUser'))
				throw new CHttpException(403);
			
			$model = User::model()->findByPk($id);
			
			if($model === null)
			{
				Yii::app()->user->setFlash("error",Yii::t('user', "The user does not exist"));
				$this->redirect(array('index'));
			}
			
			$this->breadcrumbs[] = $model->username;
		}
		
		$this->pageTitle = Yii::t('user', 'Users') . ": " . ($model->isNewRecord ? Yii::t('user', 'New User') : Yii::t('user', 'Edit User') . ': ' . $model->username );
		$this->breadcrumbs = array(
				Yii::t('user', 'Users') =>  array('index'),
				$model->isNewRecord ? Yii::t('user', 'New User') : $model->username,
		
		);
		
		if(isset($_POST['User']))
		{
		
			$model->attributes = $_POST['User'];
		
			
			$isNew = $model->isNewRecord;
			
			if($model->save())
			{
				
				if($isNew)
					Yii::app()->user->setFlash('success',Yii::t('user', 'The user has been created successfully'));
				else
					Yii::app()->user->setFlash('success',Yii::t('user', 'The user has been updated successfully'));
			
				switch($action)
				{
					case "save-and-new":
						$this->redirect(array('create'));
						break;
					
					case "save-and-stay":
						if($isNew)
							$this->redirect(array('update','id' => $model->id_user));
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
	
		if(!Yii::app()->user->checkAccess('deleteUser'))
			throw new CHttpException(403);
		
		if(Yii::app()->request->getParam('ids')!==null)
			$ids = explode(', ', Yii::app()->request->getParam('ids'));
		elseif(isset($_GET['id']))
			$ids = array($_GET['id']);
		else
			$ids = array();
				
		if(isset($_POST['ids']))
		{
			
			$criteria=User::model()->getDbCriteria();
			$criteria->addInCondition('id_user', $ids);
					
			try{
				$total = User::model()->deleteAll($criteria);
				Yii::app()->user->setFlash('success',Yii::t('user', 'The user has been deleted successfully|The users have been deleted successfully',array($total)));
			}
			catch(CException $e)
			{
				Yii::app()->user->setFlash('error',$e->getMessage());
			}
			
			$this->redirect(array('index'));
		
		}
		else
		{

			$models = User::model()->findAllByPk($ids);
			$this->layout = '/layouts/actionModal';
			$this->render('delete', array('models' => $models, 'ids' => implode(', ', $ids)));
		}
	
	}
	

}