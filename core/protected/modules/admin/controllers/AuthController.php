<?php

class AuthController extends AdminController
{
	
	public function actionIndex()
	{
		
		if(!Yii::app()->user->checkAccess('viewAuth'))
			throw new CHttpException(403);
		
		$this->pageTitle = Yii::t('auth', 'Permissions');
		
		$this->breadcrumbs[] = Yii::t('auth', 'Permissions');
		
		$roles = Yii::app()->authManager->getRoles();
		
		$this->render('index', array('roles' => $roles));
		
	}
	
	public function actionCreateUpdate($id = null)
	{
		/* @var $auth CDbAuthManager */
		$auth = Yii::app()->authManager;
		
		if($id === null)
		{
			
			if(!Yii::app()->user->checkAccess('createAuth'))
				throw new CHttpException(403);
			
			$this->pageTitle = Yii::t('auth', 'New Role');
			
			$this->breadcrumbs[Yii::t('auth', 'Permissions')] = array('index');
			$this->breadcrumbs[] = Yii::t('auth', 'New Role');
			
			$role = null;
			$children = array();
			
			$name = '';
		}
		else
		{
			
			if(!Yii::app()->user->checkAccess('updateAuth'))
				throw new CHttpException(403);
			
			$role = $auth->getAuthItem($id);

			$this->pageTitle = Yii::t('auth', 'Edit Role: {role}', array('{role}' => $role->name));
			
			$this->breadcrumbs[Yii::t('auth', 'Permissions')] = array('index');
			$this->breadcrumbs[] = Yii::t('auth', 'Edit Role: {role}', array('{role}' => $role->name));
			
			if($role === null)
				throw new CHttpException(404);
			
			$children = $role->getChildren();
			$name = $role->name;
		}
		
		if( Yii::app()->request->isPostRequest && isset($_POST['operation']) )
		{
			
			$name = Yii::app()->request->getPost('name');
			
			if($name == '')
			{
				Yii::app()->user->setFlash('error', Yii::t('auth', 'The role name is mandatory.'));
			}
			else
			{
				
				if($role === null)
					$role = $auth->createAuthItem($name, CAuthItem::TYPE_ROLE);
				else
				{
					if($name !== $role->name)
						$oldName = null;
					else
						$oldName = $role->name;
						
					$role->name = $name;
					$auth->saveAuthItem($role, $oldName);
				}

				
				foreach( $role->getChildren() as $child => $item)
				{
					if(Yii::app()->user->checkAccess($child))
						$role->removeChild($child);
				}
				
				foreach($_POST['operation'] as $operation)
				{
					if(Yii::app()->user->checkAccess($operation))
						$role->addChild($operation);
				}
				
				$children = $role->getChildren();

				$action = Yii::app()->request->getPost('action', 'save-and-close');
				
				switch($action)
				{
					case 'save-and-stay':
						$this->redirect(array('update', 'id' => $name));	
					break;
					
					case 'save-and-new':
						$this->redirect(array('create'));
					break;
					
					case 'save-and-close':
					default:
							$this->redirect(array('index'));
					break;
				}
				
			}
			
		}

		$this->render('form', array('name' => $name, 'groups' => $this->getOperations(), 'children' => $children));
		
	}
	
	
	public function getOperations()
	{
		
		$core = array (
						Yii::t('auth', 'Roles') => array(	Yii::t('auth','View') => 'viewAuth',
															Yii::t('auth','Create') => 'createAuth',
															Yii::t('auth','Update') => 'updateAuth',
															Yii::t('auth','Delete') => 'deleteAuth',
														),
						Yii::t('admin', 'Administrators') => array(
															Yii::t('auth','View') => 'viewAdministrator',
															Yii::t('auth','Create') => 'createAdministrator',
															Yii::t('auth','Update') => 'updateAdministrator',
															Yii::t('auth','Delete') => 'deleteAdministrator'
														),
						Yii::t('user', 'Users') => array(
															Yii::t('auth','View') => 'viewUser',
															Yii::t('auth','Create') => 'createUser',
															Yii::t('auth','Update') => 'updateUser',
															Yii::t('auth','Delete') => 'deleteUser'
														),
						Yii::t('page', 'Pages') => array(
															Yii::t('auth','View') => 'viewPage',
															Yii::t('auth','Create') => 'createPage',
															Yii::t('auth','Update') => 'updatePage',
															Yii::t('auth','Delete') => 'deletePage'
														),
						Yii::t('blog', 'Blogs') => array(
															Yii::t('auth','View') => 'viewBlog',
															Yii::t('auth','Create') => 'createBlog',
															Yii::t('auth','Update') => 'updateBlog',
															Yii::t('auth','Delete') => 'deleteBlog'
														),
						Yii::t('page', 'Categories') => array(
															Yii::t('auth','View') => 'viewCategory',
															Yii::t('auth','Create') => 'createCategory',
															Yii::t('auth','Update') => 'updateCategory',
															Yii::t('auth','Delete') => 'deleteCategory'
															),
						Yii::t('blog', 'Posts') => array(
															Yii::t('auth','View') => 'viewPost',
															Yii::t('auth','Create') => 'createPost',
															Yii::t('auth','Update') => 'updatePost',
															Yii::t('auth','Delete') => 'deletePost'
														),
						Yii::t('menu', 'Menus') => array(
															Yii::t('auth','View') => 'viewMenu',
															Yii::t('auth','Create') => 'createMenu',
															Yii::t('auth','Update') => 'updateMenu',
															Yii::t('auth','Delete') => 'deleteMenu'
														),
						Yii::t('menu', 'Menu Items') => array(
															Yii::t('auth','View') => 'viewMenuItem',
															Yii::t('auth','Create') => 'createMenuItem',
															Yii::t('auth','Update') => 'updateMenuItem',
															Yii::t('auth','Delete') => 'deleteMenuItem'
														),
						Yii::t('lang', 'Languages') => array(
															Yii::t('auth','View') => 'viewLanguage',
															Yii::t('auth','Update') => 'updateLanguage'
															),

						Yii::t('backup', 'Backups') => array(
															Yii::t('auth','View') => 'viewBackup',
															Yii::t('auth','Create') => 'createBackup',
															Yii::t('backup', 'Restore') => 'restoreBackup',
															Yii::t('auth','Delete') => 'deleteBackup'
															),
						Yii::t('migration', 'Updates') => array(
															Yii::t('auth','View') => 'viewMigration',
															Yii::t('migration', 'Update') => 'migrateMigration'
															),
			);
		
		
		$modules = array();
		
		foreach(Yii::app()->moduleManager->modulesConfig as $module)
		{
			if(isset($module->auths))
			{
				$modules = CMap::mergeArray($modules, $module->auths);
			}
		}
		
		return CMap::mergeArray($core, $modules);
		
	}
	
	public function actionDelete()
	{
		if(!Yii::app()->user->checkAccess('deleteAuth'))
			throw new CHttpException(403);
		
		
		if(isset($_POST['role']))
		{
			
			Yii::app()->authManager->removeAuthItem($_POST['role']);
			Yii::app()->user->setFlash('success', Yii::t('auth', 'The role has been deleted successfully.'));
			$this->redirect(array('index'));
		}
		else
		{
			$role = Yii::app()->request->getQuery('id', null);
			$this->layout = '/layouts/actionModal';
			$this->render('delete', array('role' => $role));
		}
		
	}
	
}