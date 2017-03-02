<?php


class DefaultController extends AdminController{
	
	
	public function actionIndex()
	{
		

		if(!Yii::app()->user->checkAccess('viewSlider'))
			throw new CHttpException(403);
		
		$this->breadcrumbs[] =  Yii::t('SliderModule.main', 'Sliders');
		$this->pageTitle = Yii::t('SliderModule', 'Sliders');
			
		$this->render('index',array(
				'dataProvider' => new CActiveDataProvider('Slider'),
		
		));
		
	}
	
	public function actionCreate()
	{
		$this->forward('createUpdate');
	}
	
	
	public function actionUpdate($id)
	{
		$this->forward('createUpdate');
	}
	
	public function actionCreateUpdate($id = null)
	{
	
		$action = isset($_POST['action']) ? $_POST['action'] : 'save-and-close';
		
		$this->breadcrumbs[Yii::t('SliderModule.main', 'Sliders')] =  array('index');
	
		if($id === null)
		{
			if(!Yii::app()->user->checkAccess('createSlider'))
				throw new CHttpException(403);
			
			
			$model = new Slider();
			
			$this->breadcrumbs[] = Yii::t('SliderModule.main', 'New Slider');
			$this->pageTitle = Yii::t('SliderModule.main', 'New Slider');
			$slides = array();
			
			
		}
		else
		{
			
			if(!Yii::app()->user->checkAccess('updateSlider'))
				throw new CHttpException(403);
			
			$model = Slider::model()->findByPk($id);
				
			if($model === null)
			{
				throw new CHttpException(404);
			}
			
			$this->breadcrumbs[] = $model->name;
			$this->pageTitle = $model->name;
			
		}
	
		
	
		if(isset($_POST['Slider']))
		{
	
			$model->attributes = $_POST['Slider'];
			$isNew = $model->isNewRecord;
			
			$slides = array();
			$valid = true;
			if(isset($_POST['Slide']))
			{
				$pos = 0;
				foreach($_POST['Slide'] as $slideArray)
				{
					
					if($slideArray['image'] == '')
						continue;
					
					$slide = new Slide();
					$slide->attributes = $slideArray;
					$slide->position = $pos++;
				
					$slides[] = $slide;
					
				}	
			}
						
			$transaction = $model->dbConnection->beginTransaction();
			
			if(isset($_POST['add-slide']))
			{
				$slides[] = new Slide();
			}
			elseif($model->save())
			{
				if($isNew)
					Yii::app()->user->setFlash('success',Yii::t('SliderModule.main', 'The Slider has been created successfully'));
				else
				{					

					
					Slide::model()->deleteAll('id_slider = :id', array('id' => $model->id_slider));
					
					foreach($slides as $slide)
					{
						$slide->id_slider = $model->id_slider;
						$slide->save();
					}
					
					Yii::app()->user->setFlash('success',Yii::t('SliderModule.main', 'The Slider has been modified successfully'));

				}
				
				$transaction->commit();
	
				switch($action)
				{
					case "save-and-new":
						$this->redirect(array('create'));
						break;
							
					case "save-and-stay":
						if($isNew)
							$this->redirect(array('update','id' => $model->id_slider));
						break;
							
					case "save-and-close":
					default:
						$this->redirect( array('index'));
						break;
				}
	
			}
			else
			{
				Yii::app()->user->setFlash('error',CHtml::errorSummary(CMap::mergeArray(array($model), $slides),null,null,array('class' => 'message error errorSummary')));
			}
	
		}
		else
		{
			$slides = $model->slides;
		}
		
		$this->render('form',array('model' => $model, 'slides' => $slides));
			
	}

	
	public function actionDelete()
	{
	
		if(!Yii::app()->user->checkAccess('deleteSlider'))
			throw new CHttpException(403);
		
		if(Yii::app()->request->getParam('ids')!==null)
			$ids = explode(', ', Yii::app()->request->getParam('ids'));
		elseif(isset($_GET['id']))
			$ids = array($_GET['id']);
		else
			$ids = array();
	
	
		if(isset($_POST['ids']))
		{
			$criteria=Slider::model()->getDbCriteria();
			$criteria->addInCondition('id_slider', $ids);
		
			$total = Slider::model()->deleteAll($criteria);
				
			Yii::app()->user->setFlash('success',Yii::t('SliderModule.main', 'The Slider has been deleted successfully|The Sliders have been deleted successfully',array($total)));
			$this->redirect(array('index'));
		
		}
		else
		{
			$models = Slider::model()->findAllByPk($ids);
			
			$criteria = new CDbCriteria();
			$criteria->addInCondition('id_slider', $ids);
			
			$this->layout = 'application.modules.admin.views.layouts.actionModal';
			$this->render('delete', array('models' => $models, 'ids' => implode(', ', $ids)));
		}
	
	}
	
}