<?php

class SocioController extends Controller{
	
	
	public function actionCreate()
	{
		
		$model = new Socio();
		$model->scenario = 'default';
		
		if(isset($_POST['Socio']))
		{

			$model->attributes = $_POST['Socio'];

			$model->scenario = $model->type == Socio::TYPE_JURIDIC ? 'juridic' : ($model->type == Socio::TYPE_PHYSIC ? 'physic' : null); 
			
			if($model->save())
			{
				//Enviar mail al administrador
				$mail = new YiiMailMessage();
				$mail->setTo(Yii::app()->params->adminEmail);
				//$mail->setTo("benoit.boure@gmail.com");
				$mail->setFrom(Yii::app()->params->adminEmail);
				$mail->setSubject(Yii::t('SocioModule.main', 'Un nuevo socio se ha dado de alta'));
				$body = $this->renderPartial('modules.socio.views.mail.subscribe',array('model' => $model),true);
				
				$mail->setBody($body, 'text/html','UTF-8');
				Yii::app()->mail->send($mail);
				
				//Enviar mail al socio
				/*
				$mail = new YiiMailMessage();
				$mail->setTo($model->email);
				$mail->setFrom(Yii::app()->params->adminEmail);
				$mail->setSubject(Yii::t('SocioModule.main', 'Gracias por darse de alta como Socio de OCULARIS'));
				$body = $this->renderPartial('application.modules.socio.views.mail.subscribed',array('model' => $model),true);
				$mail->setBody($body, 'text/html','UTF-8');
				Yii::app()->mail->send($mail);
				*/
				
				Yii::app()->user->setFlash('socio_success',Yii::t('SocioModule.main', 'Thank you for your support'));
			}
			
		}
		
		
		$this->render('create', array('model' => $model));
		
	}
	
}