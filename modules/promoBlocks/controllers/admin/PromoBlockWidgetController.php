<?php


class PromoBlockWidgetController extends Controller{
	
	
	public function actionIndex()
	{
		
		$this->pageTitle = Yii::t('PromoBlocksModule.main', 'Block Widgets');
		
		$action = isset($_POST['action']) ? $_POST['action'] : '';
		
		switch($action)
		{
			case 'delete-block':
				$this->delete();
				break;
		}
		
		
		$model=new PromoBlockWidget('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['PromoBlockWidget']))
			$model->attributes=$_GET['PromoBlockWidget'];
		
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
			$model = new PromoBlockWidget();
			$blocks = array();
		}
		else
		{
			$model = PromoBlockWidget::model()->findByPk($id);
				
			if($model === null)
			{
				Yii::app()->user->setFlash("error",Yii::t('PromoBlocksModule.main', "The Block Widget does not exist"));
				$this->redirect(array('index'));
			}
			
			$blocks = $model->promoBlocks;
		}
	
		if(isset($_POST['PromoBlockWidget']))
		{
	
			$model->attributes = $_POST['PromoBlockWidget'];
			$blocks = array();
			
			if(isset($_POST['PromoBlockWidgetPromoBlock']))
			{
				foreach($_POST['PromoBlockWidgetPromoBlock'] as $promo_block)
				{
					$block = new PromoBlockWidgetPromoBlock();
					$block->attributes = $promo_block;
					$blocks[] = $block;
				} 
			}
			
			$isNew = $model->isNewRecord;
			
			
			$transaction = $model->getDbConnection()->beginTransaction();
			
			try{
			
			
				if($model->save())
				{
					
					if(!$isNew)
					{
						//delete all blocksassoc from DB
						PromoBlockWidgetPromoBlock::model()->deleteAll("id_promo_block_widget = :id",array(":id" => $model->id_promo_block_widget));
					}
					
					
					if($isNew)
						Yii::app()->user->setFlash('success',Yii::t('PromoBlocksModule.main', 'The Block Widget was created successfully'));
					else
						Yii::app()->user->setFlash('success',Yii::t('PromoBlocksModule.main', 'The Block Widget was modified successfully'));
		
					
					foreach($blocks as $block)
					{
						$block->id_promo_block_widget = $model->id_promo_block_widget;
						if(!$block->save())
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
								$this->redirect(array('update','id' => $model->id_promo_block_widget));
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
		$this->pageTitle = Yii::t('PromoBlocksModule.main', 'Bloques de la página principal') .': ' . ($model->isNewRecord ? Yii::t('PromoBlocksModule.main', 'Nuevo Widget de Bloque') : Yii::t('PromoBlocksModule.main', 'Editar Widget de Bloque') . ": " . $model->name);
		
		$this->breadcrumbs = array(
				Yii::t('PromoBlocksModule', 'Widget') =>  array('index'),
				$model->isNewRecord ? Yii::t('PromoBlocksModule', 'Nuevo bloque') : $model->name,
		
		);
		
		$this->render('form',array('model' => $model, 'blocks' => $blocks));
			
	}
	
	
	private function delete()
	{
	
		$ids = explode(",",$_POST['ids']);
	
		$models = PromoBlockWidget::model()->findAllByPk($ids);
	
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
	
		$transaction = PromoBlockWidget::model()->getDbConnection()->beginTransaction();
	
		$criteria=PromoBlockWidget::model()->getDbCriteria();
		$criteria->addInCondition('id_promo_block_widget', $ids);
	
		$total = PromoBlockWidget::model()->deleteAll($criteria);
		$transaction->commit();
			
		Yii::app()->user->setFlash('success',Yii::t('PromoBlocksModule.main', 'The Block Widget has been deleted successfully|The Block Widgets have been deleted successfully',array($total)));
		$this->redirect(array('index'));
	
	}
	
}