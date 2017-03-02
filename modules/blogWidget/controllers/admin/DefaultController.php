<?php


class DefaultController extends Controller{
	
	
	public function actionIndex()
	{
		
		$this->pageTitle = Yii::t('BlogWidgetModule', 'Blog Widgets');
		
		$action = isset($_POST['action']) ? $_POST['action'] : '';
		
		switch($action)
		{
			case 'delete-widget':
				$this->delete();
				break;
		}
		
		
		$model=new BlogWidget('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['BlogWidget']))
			$model->attributes=$_GET['BlogWidget'];
		
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
			$model = new BlogWidget();
		}
		else
		{
			$model = BlogWidget::model()->findByPk($id);
				
			if($model === null)
			{
				Yii::app()->user->setFlash("error",Yii::t('BlogWidgetModule.main', "The widget does not exist"));
				$this->redirect(array('index'));
			}
		}
	
		$this->pageTitle = Yii::t('BlogWidgetModule.main', 'Blog Widgets') .': ' . ($model->isNewRecord ? Yii::t('BlogWidgetModule.main', 'New widget') : Yii::t('BlogWidgetModule.main', 'Edit Widget') . ": " . $model->title);
	
		if(isset($_POST['BlogWidget']))
		{
	
			$model->attributes = $_POST['BlogWidget'];
	
				
			$isNew = $model->isNewRecord;
				
			if($model->save())
			{
				if($isNew)
					Yii::app()->user->setFlash('success',Yii::t('BlogWidgetModule.main', 'The news widget has been created successfully'));
				else
					Yii::app()->user->setFlash('success',Yii::t('BlogWidgetModule.main', 'The news widget has been modified successfully'));
	
				switch($action)
				{
					case "save-and-new":
						$this->redirect(array('create'));
						break;
							
					case "save-and-stay":
						if($isNew)
							$this->redirect(array('update','id' => $model->id_widget));
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
	
	
	private function delete()
	{
	
		$ids = explode(",",$_POST['ids']);
	
		$models = BlogWidget::model()->findAllByPk($ids);
	
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
	
		$transaction = BlogWidget::model()->getDbConnection()->beginTransaction();
	
		$criteria=Blog::model()->getDbCriteria();
		$criteria->addInCondition('id_widget', $ids);
	
		$total = BlogWidget::model()->deleteAll($criteria);
		$transaction->commit();
			
		Yii::app()->user->setFlash('success',Yii::t('BlogWidgetModule.main', 'The news widget has been deleted successfully|The Blog Widgets have been deleted successfully',array($total)));
		$this->redirect(array('index'));
	
	}
	
}