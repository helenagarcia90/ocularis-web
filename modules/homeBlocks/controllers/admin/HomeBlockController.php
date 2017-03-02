<?php


class HomeBlockController extends AdminController{
	
	
	public function actionIndex()
	{
		
		if(!Yii::app()->user->checkAccess('viewHomeBlock'))
			throw new CHttpException(403);
		
		$this->pageTitle = Yii::t('HomeBlocksModule.main', 'Home Blocks');
	
		$this->render('index',array(
				'dataProvider' => new CActiveDataProvider('HomeBlock'),
		
		));
		
	}
	
	public function actionCreateUpdate($id = null)
	{
	
		$action = isset($_POST['action']) ? $_POST['action'] : 'save-and-close';
	
		$this->breadcrumbs = array(
				Yii::t('HomeBlocksModule.main', 'Home blocks') =>  array('index'),
		);
		
		
		if($id === null)
		{
			if(!Yii::app()->user->checkAccess('createHomeBlock'))
				throw new CHttpException(403);

			$this->breadcrumbs[] = $this->pageTitle = Yii::t('HomeBlocksModule.main', 'New Block');
				
			
			$model = new HomeBlock();
			
			$model->position = Yii::app()->db->createCommand()->select('MAX(`position`) + 1')->from('{{home_block}}')->queryScalar();
		}
		else
		{

			if(!Yii::app()->user->checkAccess('updateHomeBlock'))
				throw new CHttpException(403);
			
			$model = HomeBlock::model()->findByPk($id);
			
			if($model === null)
			{
				throw new CHttpException(404);
			}
			
			$this->breadcrumbs[] = $this->pageTitle = $model->title;
		}
	
		
		if(isset($_POST['HomeBlock']))
		{
	
			$model->attributes = $_POST['HomeBlock'];
				
			$isNew = $model->isNewRecord;

				if($model->save())
				{

					if($isNew)
						Yii::app()->user->setFlash('success',Yii::t('HomeBlocksModule.main', 'The Block was created successfully'));
					else
						Yii::app()->user->setFlash('success',Yii::t('HomeBlocksModule.main', 'The Block was modified successfully'));
					
					switch($action)
					{
						case "save-and-new":
							$this->redirect(array('create'));
							break;
								
						case "save-and-stay":
							if($isNew)
								$this->redirect(array('update','id' => $model->id_home_block));
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

	
	public function actionDelete()
	{

			if(!Yii::app()->user->checkAccess('deleteHomeBlock'))
				throw new CHttpException(403);
			
			if(Yii::app()->request->getParam('ids')!==null)
				$ids = explode(', ', Yii::app()->request->getParam('ids'));
			
			elseif(isset($_GET['id']))
				$ids = array($_GET['id']);
			else
				$ids = array();
			
			if(isset($_POST['ids']))
			{
			
				$transaction = HomeBlock::model()->getDbConnection()->beginTransaction();
			
				$criteria=HomeBlock::model()->getDbCriteria();
				$criteria->addInCondition('id_home_block', $ids);
			
				$total = HomeBlock::model()->deleteAll($criteria);
				$transaction->commit();
					
				Yii::app()->user->setFlash('success',Yii::t('HomeBlocksModule.main', 'The Block has been deleted successfully|The Blocks have been deleted successfully',array($total)));
				$this->redirect(array('index'));
		
			}
			else {
				
				$models = HomeBlock::model()->findAllByPk($ids);
				$this->layout = 'application.modules.admin.views.layouts.actionModal';
					
				$this->render('delete', array('models' => $models, 'ids' => implode(', ', $ids)));
				
			}
		
	}
	
}