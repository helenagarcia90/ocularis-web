<?php

Yii::import('zii.widgets.CBaseListView');
Yii::import('zii.widgets.grid.CDataColumn');
Yii::import('application.modules.admin.components.widgets.grid.*');

class ModelGridView extends CBaseListView
{
	
	/**
	 * @property CActiveDataProvider $dataProvider
	 * 
	 * */
	public $dataProvider;
	
	public $title;
	
	public $controller;
	
	public $columns = array();
	public $filter;
	public $blankDisplay = '&nbsp;';
	public $_formatter;
	public $nullDisplay;
	public $template = '{items}{pager}';	
	
	public $rowHtmlOptions = array(); 
	public $rowHtmlData = array();
	
	public $actions = array('update', 'delete');
	public $bulkActions = array('delete');
	public $headingActions = array('new','bulk');
	
	public $filters = array();
	public $pageSize = 20;
	
	public function init()
	{

		if(!$this->dataProvider instanceof CActiveDataProvider)
			throw new CException('The dataprovider must be an instance of CActiveDataProvider');
		
		if($this->controller === null)
			$this->controller = Yii::app()->controller->id;
		
		if($this->title === null)
			$this->title = Yii::app()->controller->pageTitle;
			
		$columns = array();
		
		if($this->bulkActions!==false)
		{
			$col = new CheckboxColumn($this);
			$col->name = $this->dataProvider->model->tableSchema->primaryKey;
			$col->headerHtmlOptions = $col->htmlOptions = array('class' => 'checkbox-column');
			$columns[] = $col;
		}
		
		$col = new DataColumn($this);
		$col->name = $this->dataProvider->model->tableSchema->primaryKey;
		$col->header = 'ID';
		$col->headerHtmlOptions = $col->htmlOptions = array('class' => 'col-xs text-right hidden-xs');
		$col->htmlOptions = $col->htmlOptions = array('class' => 'col-xs text-right hidden-xs');
		$col->filterHtmlOptions = $col->htmlOptions = array('class' => 'col-xs text-right hidden-xs');
		$columns[] = $col;
		
		foreach($this->columns as $column)
		{
			if(is_string($column))
			{
				$col = new DataColumn($this);
				$col->name = $column;
				$columns[] = $col;
			}
			else
			{
				if(!isset($column['class']))
					$column['class'] = 'DataColumn';
				
				$columns[] = Yii::createComponent($column,$this);
			}
		}
		
		if($this->actions!==false)
		{
			$col = new ActionColumn($this);
			$col->name = $this->dataProvider->model->tableSchema->primaryKey;
			$col->sortable = false;
			$col->header = Yii::t('modelGridView', 'Actions');
			$col->headerHtmlOptions = $col->htmlOptions = array('class' => 'actions-column');
			$columns[] = $col;
		}
		
		$this->columns = $columns;
		
		$sort = $this->dataProvider->getSort();
		
		/* @var $criteria CDbCriteria */
		$criteria = $this->dataProvider->criteria;
		$primary = $this->dataProvider->model->tableSchema->primaryKey;
		$criteria->compare($primary, $this->getParam('filter_'.$primary),false);
		$this->dataProvider->sort->attributes[] = $primary;
		
		foreach($this->columns as $column)
		{
			$column->init();
		}
		
		$this->pagerCssClass = 'panel-footer';
		
		$this->dataProvider->pagination->pageSize = $this->pageSize;
		
		parent::init();
	}
	
	public function renderContent()
	{
		
		echo CHtml::openTag('div', array('class' => 'panel panel-primary'));
		
		echo CHtml::tag('div', array('class' => 'loader'),
				CHtml::tag('span', array('class' => 'label label-warning'),
						CHtml::tag('span', array('class' => 'glyphicon glyphicon-refresh'),''). '&nbsp;' . 
						Yii::t('modelGridView', 'Loading ...')
					)
		);
		
		echo CHtml::openTag('div', array('class' => 'panel-heading'));
			
		if($this->headingActions !== false)
		{
			$params = $_GET;
			unset($params[ucfirst($this->controller)]);
			unset($params['gridId']);
			unset($params['_']);
			foreach($params as $key => $val)
				if(strpos($key, 'filter_')===0)
					unset($params[$key]);
			
			echo CHtml::openTag('div', array('class' => 'btn-group pull-right btn-group-panel btn-heading'));
			
			foreach($this->headingActions as $label => $action)
			{
				switch($action)
				{

					case 'new':
						if(Yii::app()->user->checkAccess('create'.ucfirst($this->controller)))
							echo CHtml::link('<span class="glyphicon glyphicon-plus" ></span>', array_merge(array('create'),$params),array('data-original-title' => Yii::t('modelGridView', 'New'), 'class' => 'btn btn-primary btn-sm'));
					break;
					
					case 'bulk':
						
							if($this->bulkActions!==false)
							{
								
								$actions = array();
								foreach($this->bulkActions as $label => $action)
								{
									switch($action)
									{
										case 'delete':
											if(Yii::app()->user->checkAccess('delete'.ucfirst($this->controller)))
												$actions[] = 'delete'; 
										break;
										default:
											if(is_array($action) && (!isset($action['visible']) || $action['visible']))
												$actions[$label] = $action;
										break;
									}
										
								}
								
								if($actions !== array())
								{
									//actions
									echo CHtml::openTag('div', array('class' => 'btn-group'));
									echo CHtml::link('<span class="glyphicon glyphicon-tasks" ></span>', '#',array('data-original-title' => Yii::t('modelGridView', 'Bulk Actions'), 'data-toggle' => 'dropdown', 'class' => 'btn btn-primary btn-sm dropdown-toggle'));
										
									echo CHtml::openTag('ul', array('class' => 'dropdown-menu dropdown-menu-right', 'role' =>'menu'));
								
									
									foreach($actions as $label => $action)
									{
										switch($action)
										{
											case 'delete':
												echo CHtml::tag('li', array(), CHtml::link('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('modelGridView', 'Delete'), Yii::app()->controller->createUrl('delete', $params), array('class' => "bulk_action")));
											break;
											default:
												if(is_array($action))
													$action = $action['url'];
												echo CHtml::tag('li', array(), CHtml::link($label, $action, array('class' => "bulk_action")));
											break;
										}
										
									}
								
									echo CHtml::closeTag('ul');
										
									echo CHtml::closeTag('div');
								}
							}
					break;
					
					default:

						if( !isset($action['visible']) || $action['visible'])
						{
							$htmlOptions = array();
							
							if(isset($action['htmlOptions']))
								$htmlOptions = $action['htmlOptions'];
							
							$htmlOptions['title'] = $label;
							$htmlOptions['data-placement'] = 'top';
	
							$classes = 'btn btn-primary btn-sm tooltipped ' . (isset($action['modal']) && $action['modal'] ? ' modal_action' : '' );
							
							if(isset($htmlOptions['class']))
								$htmlOptions['class'] .= ' '.$classes;
							else
								$htmlOptions['class'] = $classes;
							
							echo CHtml::link($action['icon'], $action['url'],$htmlOptions);
						}
					break;
				}
				
				
				
			}
			
					
			
			
			
		
			echo CHtml::closeTag('div');
		}
		
		if($this->filters !== array())
		{
			$this->filters = array_reverse($this->filters);
				
			foreach($this->filters as $name => $filter)
			{
		
				echo CHtml::openTag('div', array('class' => 'filters btn-group btn-group-panel pull-right'));
				if(is_array($filter))
				{
						
					if(isset($filter[0]))
					{
						$prompt = $filter[0];
						unset($filter[0]);
					}
					else
						$prompt = false;
						
					$this->widget('DropDown', array(
							'data' => $filter,
							'model' => $this->dataProvider->model,
							'attribute' => $name,
							'options' => array ( 'label' => false, 'prompt' => $prompt, 'size' => 'xs', 'align' => 'right' ),
					));
				}
		
				echo CHtml::closeTag('div');
			}
				
				
		}
		
		echo CHtml::tag('h3', array('class' => 'panel-title'),$this->title . '&nbsp;<span class="badge count">'.$this->dataProvider->getItemCount().'</span>');
		
		echo CHtml::openTag('div', array('class' => 'clearfix'));
		echo CHtml::closeTag('div');
		
		echo CHtml::closeTag('div');
		
		parent::renderContent();
		
		echo CHtml::closeTag('div');
		
		
		
		echo <<<modal
			<div id="{$this->id}modelGridModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
		
			</div>
			</div>
			</div>
modal;
		
	}

	public function renderItems()
	{

			echo CHtml::openTag('div', array('class' => 'table-responsive'));
			echo CHtml::openTag('table', array('id' => $this->id . '_table',  'class' => 'table table-striped table-hover table-model'));
				
				$this->renderTableHeader();
				$this->renderTableBody();
				
			echo CHtml::closeTag('table');
			echo CHtml::closeTag('div');
		
		
	}
	
	public function renderTableHeader()
	{
		
		echo CHtml::openTag('thead',array(), CHtml::checkBox('',false));
		echo CHtml::openTag('tr');

		foreach($this->columns as $column)
		{
			if($column->visible)
				$column->renderHeaderCell();
		}
		
		echo CHtml::closeTag('tr');
		
		$this->renderFilter();
		
		echo CHtml::closeTag('thead');
		
	}
	
	public function renderFilter()
	{
		if($this->filter!==false)
		{
			
			echo "<tr class=\"filters\">\n";
		
			foreach($this->columns as $column)
				if($column->visible)
					$column->renderFilterCell();
			
			
			echo "</tr>\n";
		}
	}
	
	public function renderTableBody()
	{
		echo CHtml::openTag('tbody',array(), CHtml::checkBox('',false));
		
		if($this->dataProvider->getItemCount() > 0)
		{
						
			$row = 0;
			foreach ($this->dataProvider->getData() as $data)
			{
					$this->rowHtmlOptions['data-href'] = Yii::app()->controller->createUrl('update', array('id' => $data->primaryKey));
					$this->rowHtmlOptions['data-id'] = $data->primaryKey;

					foreach($this->rowHtmlData as $key => $rowData)
					{
						$this->rowHtmlOptions[$key] = $this->evaluateExpression($rowData, array('data' => $data));
					}
				
					echo CHtml::openTag('tr', $this->rowHtmlOptions);
					foreach($this->columns as $column)
					{
						if($column->visible)
							$column->renderDataCell($row);	
					}
					echo CHtml::closeTag('tr');
					$row++;
			}
		
		}
		else
		{
			echo CHtml::openTag('tr', array('class' => 'default noresult'));
			echo CHtml::openTag('td', array('colspan' => count($this->columns)));
				$this->renderEmptyText();
			echo CHtml::closeTag('td');
			echo CHtml::closeTag('tr');
		}
		
		echo CHtml::closeTag('tbody');
	}
	
	
	public function getFormatter()
	{
		if($this->_formatter===null)
			$this->_formatter=Yii::app()->format;
		return $this->_formatter;
	}
	
	public function renderKeys()
	{
		//We do not want the keys
	}
	
	public function registerClientScript()
	{

		$path = Yii::app()->assetManager->publish(
							Yii::getPathOfAlias('application.modules.admin.components.widgets.assets.grid')
		);
		
		Yii::app()->clientScript->registerCoreScript('bootstrap-js');
		Yii::app()->clientScript->registerScriptFile($path . '/jquery.modelGridView.js');
		
		$id = $this->htmlOptions['id'];
		
		Yii::app()->clientScript->registerScript($this->id.__CLASS__.'_grid', '

				$("#'.$id.'").modelGridView({ajaxUrl: \''.Yii::app()->request->requestUri.'\'});

				$(".panel-heading > .btn-group .btn").tooltip();

				
		');
				
		if($this->actions !== false)
		{
			Yii::app()->clientScript->registerScript($this->id.__CLASS__.'_modal_bulk_delete', "
			
			$('#{$this->id}').on('click', '.modal_action, .bulk_action', function(e){

				e.preventDefault();
			
				url = $(this).attr('href');
				
				if($(this).hasClass('bulk_action'))
				{
						var ids = [];
							
									jQuery('input[name=\"{$this->id}_check[]\"]:checked').each(function(){
											ids.push(jQuery(this).val());
									});
		
						if(ids.length == 0)
							return;
									
						id = encodeURIComponent(ids.join(', '));
										
						
						
						if(url.indexOf('?')> -1)
							url = url + '&ids='+id;
						else
							url = url + '?ids='+id;
				
				}
		
				$('#{$this->id}modelGridModal').modal({remote: url});
				$('#{$this->id}modelGridModal').on('hidden.bs.modal', function(){ $(this).removeData('bs.modal') });
			
				});
				
			");
		}
		
	}
	
	public function getParam($name, $default = null)
	{
		if(isset($_GET[$name]))
		{
			$value = $_GET[$name];
			return Yii::app()->session['modelGridView'.$this->id.$name] = $value;
		}
		elseif(isset(Yii::app()->session['modelGridView'.$this->id.$name]))
			return Yii::app()->session['modelGridView'.$this->id.$name];
		else
			return $default;
		
		
	}
	
}