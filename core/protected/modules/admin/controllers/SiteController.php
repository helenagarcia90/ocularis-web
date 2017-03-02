<?php

class SiteController extends AdminController
{

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->pageTitle = Yii::t('app', 'Dashboard');
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$this->breadcrumbs[] = Yii::t('app', 'Error');

		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
			{
				if($error['code'] === 403)
					$this->render('error403');
				else
					$this->render('error', array('error' => $error) );

			}
		}
	}


	public function actionLogin()
	{

		$model=new AdminLoginForm;

		//redirect to index if already loggedin
		if(!Yii::app()->user->isGuest)
			$this->redirect('index');

		// collect user input data
		if(isset($_POST['AdminLoginForm']))
		{
			$model->attributes=$_POST['AdminLoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect('index');
		}
		// display the login form
		$this->renderPartial('login',array('model'=>$model),false,true);
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect('index');
	}


	public function actionRecoverPassword()
	{

		$model = new AdminRecoverPassword();

		if(isset($_POST['AdminRecoverPassword']))
		{
			$model->attributes = $_POST['AdminRecoverPassword'];

			$user = Admin::model()->findByAttributes(array('email'=> $model->email));

			if($user !== null)
			{

				$length = 10;
				$chars = array_merge(range(0,9), range('a','z'), range('A','Z'));
				shuffle($chars);
				$password = implode(array_slice($chars, 0, $length));

				$user->password = md5($password);
				$user->save();

				$message = new YiiMailMessage();
				$message->setSubject(Yii::t('admin', 'Password recovery'));

				$msg = $this->renderPartial("admin.views.mail.recoverpassword",array('password' => $password), true) ;

				$message->setFrom(Yii::app()->params->adminEmail);
				$message->setTo($model->email);
				$message->setBody($msg,'text/html', 'UTF-8');

				Yii::app()->mail->send($message);

				Yii::app()->user->setFlash("success",Yii::t('admin', Yii::t('admin','We just sent you an email with a new password.')));
			}
			else
				Yii::app()->user->setFlash("error",Yii::t('admin', Yii::t('admin','No user exist with the given email.')));
		}

		$this->renderPartial('recoverpassword',array('model'=>$model),false,true);
	}

}