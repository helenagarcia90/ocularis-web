<?php


class PromoBlockController extends Controller{
	
	
	public function actionIndex()
	{
		
		$this->pageTitle = Yii::t('PromoBlocksModule.main', 'Blocks');
		
		$action = isset($_POST['action']) ? $_POST['action'] : '';
		
		switch($action)
		{
			case 'delete-block':
				$this->delete();
				break;
		}
		
		
		$model=new PromoBlock('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['PromoBlock']))
			$model->attributes=$_GET['PromoBlock'];
		
		$this->render('index',array(
				'model' => $model,
		
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
	
		if($id === null)
		{
			$model = new PromoBlock();
			$widgets = array();
		}
		else
		{
			$model = PromoBlock::model()->findByPk($id);
			$widgets = $model->promoBLocksWidgets;
			
			if($model === null)
			{
				Yii::app()->user->setFlash("error",Yii::t('PromoBlocksModule.main', "The Block does not exist"));
				$this->redirect(array('index'));
			}
		}
	
		$this->pageTitle = Yii::t('PromoBlocksModule.main', 'Blocks') .': ' . ($model->isNewRecord ? Yii::t('PromoBlocksModule.main', 'New Block') : Yii::t('PromoBlocksModule.main', 'Edit Block') . ": " . $model->title);
	
		if(isset($_POST['PromoBlock']))
		{
	
			$model->attributes = $_POST['PromoBlock'];
			$model->image_file = CUploadedFile::getInstance($model, 'image_file');
			$model->image_alternate_file = CUploadedFile::getInstance($model, 'image_alternate_file');
				
			$isNew = $model->isNewRecord;
			
			$widgets = array();
			
			if(isset($_POST['PromoBlockWidgetPromoBlock']))
			{
				foreach($_POST['PromoBlockWidgetPromoBlock'] as $promo_block_widget)
				{
					$widget = new PromoBlockWidgetPromoBlock();
					$widget->attributes = $promo_block_widget;
					$widgets[] = $widget;
					
				}
			}
			
			$transaction = $model->getDbConnection()->beginTransaction();
				
			try{
					
			
				if($model->save())
				{
					
					if(!$isNew)
					{
						//delete all blocksassoc from DB
						PromoBlockWidgetPromoBlock::model()->deleteAll("id_promo_block = :id",array(":id" => $model->id_promo_block));
					}
					
					if($isNew)
						Yii::app()->user->setFlash('success',Yii::t('PromoBlocksModule.main', 'The Block was created successfully'));
					else
						Yii::app()->user->setFlash('success',Yii::t('PromoBlocksModule.main', 'The Block was modified successfully'));
		
					
					foreach($widgets as $widget)
					{
						$widget->id_promo_block = $model->id_promo_block;
																	
						if(!$widget->save())
							throw new CException("Could not save a Promo Block Association");
					}
						
					$transaction->commit();
					
					switch($action)
					{
						case "save-and-new":
							$this->redirect(array('create'));
							break;
								
						case "save-and-stay":
							if($isNew)
								$this->redirect(array('update','id' => $model->id_promo_block));
							break;
								
						case "save-and-close":
						default:
							$this->redirect( array('index'));
							break;
					}
		
				}
				
			}
			catch(CException $e)
			{
				$transaction->rollback();
				throw $e;
			}
		
		}
				
		$this->render('form',array('model' => $model, 'widgets' => $widgets));
			
	}
	
	
	private function delete()
	{
	
		$ids = explode(",",$_POST['ids']);
	
		$models = PromoBlock::model()->findAllByPk($ids);
	
		$this->question = $this->renderPartial('delete',
				array(
						'ids' => $_POST['ids'],
						'models' => $models,
				)
				,true);
	
	}
	
	public function actionDelete()
	{
	
		if(isset($_POST['cancel']))
			$this->redirect(array('index'));
	
		$ids = explode(',', $_POST['ids']);
	
		$transaction = PromoBlock::model()->getDbConnection()->beginTransaction();
	
		$criteria=PromoBlock::model()->getDbCriteria();
		$criteria->addInCondition('id_promo_block', $ids);
	
		$total = PromoBlock::model()->deleteAll($criteria);
		$transaction->commit();
			
		Yii::app()->user->setFlash('success',Yii::t('PromoBlocksModule.main', 'The Block has been deleted successfully|The Blocks have been deleted successfully',array($total)));
		$this->redirect(array('index'));
	
	}
	
}