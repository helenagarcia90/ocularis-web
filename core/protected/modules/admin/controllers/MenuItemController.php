<?php

class MenuItemController extends AdminController
{
	public function actionIndex($id_menu = null, $id_menu_item = null)
	{
		
		if(!Yii::app()->user->checkAccess('viewMenuItem'))
			throw new CHttpException(403);
		
		$criteria = new CDbCriteria();
		
		$this->breadcrumbs[Yii::t('menu', 'Menus')] = array('menu/index');
		
		if($id_menu_item!==null)
		{
		
			$parent = MenuItem::model()->findByPk($id_menu_item);
			$menu = $parent->menu;

			$criteria->addColumnCondition(array(
				'id_menu_item_parent' => $id_menu_item,
				'id_menu' => $menu->id_menu,
			));
			
			if($parent->id_menu_item_parent !== null)
				$this->breadcrumbs[] = $menu->name;
			else
				$this->breadcrumbs[$menu->name] = array('index', 'id_menu' => $menu->id_menu);
			
			$items = array();
			$parent2 = $parent;
			$i = 0;
			while($parent2 != null)
			{
				if($i==0)
					$items[] = $parent2->label;
				else
					$items[$parent2->label] = array('index','id' => $parent2->id_menu_item);
					
				$parent2 = $parent2->parent;
				$i++;
			}

			
			$items = array_reverse($items);
				
			$this->breadcrumbs += $items;
			
		}
		elseif($id_menu !== null)
		{
			$menu = Menu::model()->findByPk($id_menu);
			
			$criteria->addColumnCondition(array(
					'id_menu' => $menu->id_menu,
					'id_menu_item_parent' => null,
			));
			
			$this->breadcrumbs[] = $menu->name;
		}
		else
		{
			//no menu or menu item selected. Redirect to Menu
			$this->redirect(array('menu/index'));
		}
				
		$dataProvider = new CActiveDataProvider('MenuItem', array('criteria' => $criteria));
		
		
		$this->render('index',array(
				'dataProvider' => $dataProvider,
				
		));
		
	}

	public function actionCreateUpdate($id = null,$id_menu = null, $id_menu_item = null)
	{
	
		$this->breadcrumbs[Yii::t('menu', 'Menus')] = array('menu/index');
		
		$action = isset($_POST['action']) ? $_POST['action'] : 'save-and-close';
	
		if($id === null)
		{
			if(!Yii::app()->user->checkAccess('createMenuItem'))
				throw new CHttpException(403);
			
			$model = new MenuItem();
			
			if($id_menu !== null)
				$model->id_menu = $id_menu;
			if($id_menu_item != null)
				$model->id_menu_item_parent = $id_menu_item;
			
			$this->breadcrumbs[] = Yii::t('menu', 'New Menu Item');
			
		}
		else
		{
			
			if(!Yii::app()->user->checkAccess('updateMenuItem'))
				throw new CHttpException(403);
			
			$model = MenuItem::model()->findByPk($id);
				
			if($model === null)
			{
				Yii::app()->user->setFlash("error",Yii::t('menu', "The menu item does not exist"));
				$this->redirect(array('index'));
			}
			
			$this->breadcrumbs[] = $model->label;
		}
	
		$this->pageTitle = Yii::t('menu', 'Menu Items') . ": " . ($model->isNewRecord ? Yii::t('menu', 'New Menu Item') : Yii::t('menu', 'Edit Menu Item') . ': ' . $model->label );
		
		if(isset($_POST['MenuItem']))
		{
	
				
			$isNew = $model->isNewRecord;
			
			$trans = $model->getDbConnection()->beginTransaction();
			
			if($isNew)
			{
				
				$model->attributes = $_POST['MenuItem'];
				
				if($model->position!=null)
				{
					//incrementar los que vienen despues
					MenuItem::model()->updateAll(array('position' => new CDbExpression("`position`+1")),("`position` >= :position AND id_menu = :id_menu AND ") . ($model->id_menu_item_parent != null ? "id_menu_item_parent = {$model->id_menu_item_parent}" : "id_menu_item_parent IS NULL"),array(':id_menu' => $model->id_menu, ':position' => $model->position));
				}
			}
			else
			{
				$old_position = $model->position;
				$old_menu = $model->id_menu;
				$old_parent = $model->id_menu_item_parent;
							
				$model->attributes = $_POST['MenuItem'];
				
				if($model->position!=null)
				{
				
					$new_position = $model->position;
					
					// si el orden ha cambiado
					if($old_menu == $model->id_menu && $old_parent == $model->id_menu_item_parent)
					{
						if($old_position != $new_position)
						{
							if($old_position < $new_position)
							{
								$model->position = $new_position-1; //si se pone despues de X, X va a cambiar de orden (-1), entonces hay que quitarlo (toma su lugar)
								MenuItem::model()->updateAll(array('position' => new CDbExpression("`position`-1")),
												"`position` > :old_position AND `position` <= :new_position AND id_menu = :id_menu AND " . ($model->id_menu_item_parent != null ? "id_menu_item_parent = {$model->id_menu_item_parent}" : "id_menu_item_parent IS NULL"),
												array(':id_menu' => $model->id_menu, ':old_position' => $old_position,':new_position' => $model->position));
							}
							//se ha movido antes
							elseif($old_position > $new_position)
							{
								$model->position = $new_position;
								MenuItem::model()->updateAll(array('position' => new CDbExpression("`position`+1")),
												"`position` < :old_position AND `position` >= :new_position AND id_menu = :id_menu AND " . ($model->id_menu_item_parent != null ? "id_menu_item_parent = {$model->id_menu_item_parent}" : "id_menu_item_parent IS NULL"),
												array(':id_menu' => $model->id_menu, ':old_position' => $old_position,':new_position' => $model->position));
							}
								
						}
	
					}
					else
					{

						//move back the positions of old parent/menu
						MenuItem::model()->updateAll(array('position' => new CDbExpression("`position`-1")),
						"`position` > :old_position AND id_menu = :id_menu AND " . ($old_parent != null ? "id_menu_item_parent = {$old_parent}" : "id_menu_item_parent IS NULL"),
						array(':id_menu' => $old_menu, ':old_position' => $old_position));
						
						//move forward the new menu items
						MenuItem::model()->updateAll(array('position' => new CDbExpression("`position`+1")),
						"`position` >= :new_position AND id_menu = :id_menu AND " . ($model->id_menu_item_parent != null ? "id_menu_item_parent = {$model->id_menu_item_parent}" : "id_menu_item_parent IS NULL"),
						array(':id_menu' => $model->id_menu, ':new_position' => $model->position));

					}
					
					
				}
					
			}
			
			if($model->save())
			{
	
				$trans->commit();
				
				if($isNew)
					Yii::app()->user->setFlash('success',Yii::t('menu', 'The menu item has been created successfully'));
				else
					Yii::app()->user->setFlash('success',Yii::t('menu', 'The menu item has been updated successfully'));

				//invalidate cache
				Yii::app()->cache->delete("MenuWidget.{$model->menu->anchor}.{$model->menu->lang}");
				
				switch($action)
				{
					case "save-and-new":
						
						$url = array('create');
						
						if(isset($model->id_menu_item_parent))
							$url['id_menu_item'] = $model->id_menu_item_parent;
						else
							$url['id_menu'] = $model->id_menu;
						
						$this->redirect($url);
						break;
							
					case "save-and-stay":
						if($isNew)
							$this->redirect(array('update','id' => $model->id_menu_item));
						break;
							
					case "save-and-close":
					default:

						$url = array('index');
						
						if(isset($model->id_menu_item_parent))
							$url['id_menu_item'] = $model->id_menu_item_parent;
						else
							$url['id_menu'] = $model->id_menu;
						
						$this->redirect($url);
						break;
				}
	
			}
			else
			{
				$trans->rollback();
			}
	
		}
		else
		{
			if(isset($id_menu_item))
			{
				$item = MenuItem::model()->findByPk($id_menu_item);
				
				if($item !== null)
				{
					$model->id_menu_item_parent = $id_menu_item;
					$model->id_menu = $item->id_menu;
				}
			}
		}
					
		$this->render('form',array('model' => $model, 'id_menu' => $id_menu, 'id_menu_item' => $id_menu_item));
			
	}
	
	public function actionFindItems($id_menu = null,  $id_menu_item_parent = null)
	{
		$return = array();
		
		foreach(MenuItem::getListData($id_menu, $id_menu_item_parent) as $key => $val)
			$return[] = array('id' => $key, 'label' => $val);
		
		echo CJSON::encode($return);
		Yii::app()->end();
	}
	
	public function actionFindPositions($id_menu = null, $id_menu_item_parent = null, $id_menu_item = null)
	{
		echo CJSON::encode(MenuItem::getPositionListData($id_menu, $id_menu_item_parent, $id_menu_item));
		Yii::app()->end();
	}
	

	/*
	public function actionAjaxMenuItemCreator($model)
	{
		
		
		switch($model)
		{
			case "";
				echo "";
				Yii::app()->end();
			break;
			case "custom":
				$this->renderPartial('_menuItemCustom');
			break;
			case "external":
				$this->renderPartial('_menuItemExternal');
			break;
		}
		
		
		if(strpos($model, "_")>0)
		{
			$path = explode("_", $model);
			array_splice($path, 1, 0, array('views.admin'));
			$model= "modules.".implode(".", $path);
		}
		else
			$model = "application.views.admin.".str_replace("_", ".", $model);
		
		$this->renderPartial($model.'._menuItem');
	}
	*/
	
	public function actionDelete($id_menu = null, $id_menu_item = null)
	{
	
		if(!Yii::app()->user->checkAccess('deleteMenuItem'))
			throw new CHttpException(403);
		
		if(Yii::app()->request->getParam('ids')!==null)
			$ids = explode(', ', Yii::app()->request->getParam('ids'));
		elseif(isset($_GET['id']))
			$ids = array($_GET['id']);
		else
			$ids = array();
		
		if(isset($_POST['ids']))
		{
	
			$transaction = Category::model()->getDbConnection()->beginTransaction();

			$criteria= new CDbCriteria();
			$criteria->addInCondition('id_menu_item', $ids);
			$total = MenuItem::model()->deleteAll($criteria);
			
			if(isset($id_menu_item))
				$items = MenuItem::model()->findAllByAttributes(array('id_menu_item_parent' => $id_menu_item), array('order' => 'position ASC'));
			elseif(isset($id_menu))
				$items = MenuItem::model()->findAllByAttributes(array('id_menu' => $id_menu, 'id_menu_item_parent' => null), array('order' => 'position ASC'));
			else
				$items = array();
			
			foreach($items as $index => $item)
			{
				$item->position = $index+1;
				$item->save();
			}
			

			$transaction->commit();
			
			//invalidate cache
			$model = Menu::model()->findByPk($id_menu);
			Yii::app()->cache->delete("MenuWidget.{$model->anchor}.{$model->lang}");
			
			Yii::app()->user->setFlash('success',Yii::t('menu', 'The menu item has been deleted successfully|The menu items have been deleted successfully',array($total)));
			
			$url = array('index');
			
			if(isset($id_menu_item))
				$url['id_menu_item'] = $id_menu_item;
			else 
				$url['id_menu'] = $id_menu;
			
			$this->redirect($url);
	
		}
		else
		{
			$models = MenuItem::model()->findAllByPk($ids);
			
			$criteria = new CDbCriteria();
			$criteria->addInCondition('id_menu_item_parent', $ids);
				
			if(MenuItem::model()->count($criteria) > 0)
				Yii::app()->user->setFlash('warning', Yii::t('menu', 'The sub items will also be deleted.') );
			
			$this->layout = '/layouts/actionModal';
			$this->render('delete', array('models' => $models, 'ids' => implode(', ', $ids)));
		}
		
	}
	

}