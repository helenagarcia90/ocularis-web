<?php

class AdministratorController extends AdminController
{
	
		
	public function actionCreateUpdate($id = null)
	{
		
		$this->breadcrumbs[Yii::t('admin', 'Administrators')] = array('index');
		
		$action = isset($_POST['action']) ? $_POST['action'] : 'save-and-close';
		
		if($id === null)
		{
			if(!Yii::app()->user->checkAccess('createAdministrator'))
				throw new CHttpException(403);
			
			$model = new Admin('create');
			$this->breadcrumbs[] = Yii::t('admin', 'New Administrator');
			
			$adminRoles = array();
			
		}
		else
		{
			
			if(!Yii::app()->user->checkAccess('updateAdministrator'))
				throw new CHttpException(403);
			
			$model = Admin::model()->findByPk($id);
			
			if($model === null)
			{
				Yii::app()->user->setFlash("error",Yii::t('admin', "The Administrator does not exist"));
				$this->redirect(array('index'));
			}
			
			$this->breadcrumbs[] = $model->name;

			$adminRoles = array_keys(Yii::app()->authManager->getRoles($model->email));
		}
		
		
		
		if(isset($_POST['Admin']))
		{
		
			$model->attributes = $_POST['Admin'];
			
			$adminRoles = Yii::app()->request->getPost('roles',array());
			
			$isNew = $model->isNewRecord;
						
			if($model->save())
			{

				$currentRoles = Yii::app()->authManager->getRoles($model->email);
				
				//revoke all
				foreach($currentRoles as $name => $role)
				{
					if(Yii::app()->user->checkAccess($name))
						Yii::app()->authManager->revoke($name, $model->email);
				}
				//assign new
				foreach($adminRoles as $name)
				{
					if(Yii::app()->user->checkAccess($name))
						Yii::app()->authManager->assign($name, $model->email);
				}	
				
				if($isNew)
					Yii::app()->user->setFlash('success',Yii::t('admin', 'The Administrator has been created successfully'));
				else
				{
			
					if(Yii::app()->user->id_admin === $model->id_admin)
					{
						
						foreach($model->metaData->columns as $attribute)
						{
							if(isset(Yii::app()->user->{$attribute->name}))
								Yii::app()->user->{$attribute->name} = $model->{$attribute->name};
						}
						
						Yii::app()->language = $model->lang;
					}
					
					Yii::app()->user->setFlash('success',Yii::t('admin', 'The Administrator has been updated successfully'));
					
				}
				
				$adminRoles = array_keys(Yii::app()->authManager->getRoles($model->email));
				
				switch($action)
				{
					case "save-and-new":
						$this->redirect(array('create'));
						break;
					
					case "save-and-stay":
						if($isNew)
							$this->redirect(array('update','id' => $model->id_admin));
					break;
					
					case "save-and-close":
					default:
						$this->redirect(array('index'));
					break;
				}

			}
		
		}
		
		
		$this->render('form',
			array(
				'model' => $model,
				'roles' => Yii::app()->authManager->getRoles(),
				'adminRoles' => $adminRoles,
					
		)
		);
	}


	public function actionIndex()
	{
	
		
		if(!Yii::app()->user->checkAccess('viewAdministrator'))
			throw new CHttpException(403);
		
		$this->breadcrumbs[] = Yii::t('admin', 'Administrators');

		$dataProvider = new CActiveDataProvider('Admin');		
		
		$this->render('index',array(
				'dataProvider' => $dataProvider,
		));
	
	}
	

	public function actionDelete()
	{
	
		if(!Yii::app()->user->checkAccess('deleteAdministrator'))
			throw new CHttpException(403);
		
		if(Yii::app()->request->getParam('ids')!==null)
			$ids = explode(', ', Yii::app()->request->getParam('ids'));
		elseif(isset($_GET['id']))
		$ids = array($_GET['id']);
		else
			$ids = array();
		
		if(isset($_POST['ids']))
		{
		
			if(in_array(Yii::app()->user->id_admin,$ids))
			{
				//cannot delete own user
				unset($ids[array_search(Yii::app()->user->id_admin, $ids)]);
				Yii::app()->user->setFlash('warning',Yii::t('admin', 'You cannot delete your own account'));
					
				if(count($ids)===0)
					$this->redirect(array('index'));
			}
			
			$criteria=Admin::model()->getDbCriteria();
			$criteria->addInCondition('id_admin', $ids);
		
			$total = Admin::model()->deleteAll($criteria);
			
			Yii::app()->user->setFlash('success',Yii::t('admin', 'The administrator has been deleted successfully|The administrators have been deleted successfully',array($total)));
			
			$this->redirect(array('index'));
		
		}
		else
		{
			$models = Admin::model()->findAllByPk($ids);
			$this->layout = '/layouts/actionModal';
			$this->render('delete', array('models' => $models, 'ids' => implode(', ', $ids)));
		}
	}

}