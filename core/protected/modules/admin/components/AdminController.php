<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AdminController extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	//public $layout='/main';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	
	public $toolbar = "";
	public $toolbarButtons = array();
	
	protected $_forcePageTitle = false;
	
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	public function getPageTitle()
	{
		
		if($this->_forcePageTitle)
			return parent::getPageTitle();
		else
		{
			$title = array();
			
			foreach($this->breadcrumbs as $label => $url)
			{
				if(is_string($label))
					$title[] = $label;
				elseif(is_string($url))
					$title[] = $url;
					  
			}
			
			return $this->pageTitle = implode(" / ", $title);
		}
	}
	
	public function setPageTitle($value)
	{
		parent::setPageTitle($value);
		$this->_forcePageTitle = true;
	}	
	
	public function filters()
	{
		return array(
				'accessControl',
		);
	}
	
	public function accessRules()
	{
		
		return array(
				array('allow',
						'users'=>array('*'),
						'actions'=>array('login', 'recoverPassword'), //everybody can login or recover password
				),
				
				array('allow',
						'users'=>array('@'), //connected users
				),
				
				array('deny',
						'users' => array('*'),
				),
		);
		
	}
	

	public function actionCreate()
	{
		$this->forward('createUpdate');
	}
	
	
	public function actionUpdate($id)
	{
		$this->forward('createUpdate');
	}
	
	public function beforeAction($action)
	{
		
		
		
		if($action->id === 'createUpdate')
		{
			$this->toolbarButtons = array(
					'save-and-close' => array(
							'submit' => 'mainForm',
							'value' => '<span class="glyphicon glyphicon glyphicon-floppy-remove"></span> ' . Yii::t('toolbar', "Save and Close"),
							'id' => 'save-and-close',
							'htmlOptions' => array('class' => 'btn-success'),
								
					),
					'save-and-new' => array(
							'submit' => 'mainForm',
							'value' => '<span class="glyphicon glyphicon glyphicon-plus"></span> ' . Yii::t('toolbar', "Save and New"),
							'id' => 'save-and-new',
							'htmlOptions' => array('class' => 'btn-primary'),
			
					),
					'save-and-stay' => array(
							'submit' => 'mainForm',
							'value' => '<span class="glyphicon glyphicon glyphicon-floppy-disk"></span> ' . Yii::t('toolbar', "Save and Stay"),
							'id' => 'save-and-stay',
							'htmlOptions' => array('class' => 'btn-primary'),
			
					),
					'close' => array(
							'link' => $this->createUrl('index'),
							'value' => '<span class="glyphicon glyphicon glyphicon-remove"></span> ' .  Yii::t('toolbar', "Close"),
							'id' => 'close',
							'htmlOptions' => array('class' => 'btn-danger'),
			
					)
			);
		}
		
		return true;
	}
	
	public function beforeRender($view)
	{

		if($this->action->id === 'createUpdate')
		{
			
	
			$this->toolbar = $this->widget('Toolbar',
					array(
							'buttons' => $this->toolbarButtons,
					)
			
					,true);
		
		}
		
		return true;
	}
}