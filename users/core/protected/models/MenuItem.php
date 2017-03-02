<?php

/**
 * This is the model class for table "menu_item".
 *
 * The followings are the available columns in table 'menu_item':
 * @property string $id_menu_item
 * @property string $id_menu
 * @property string $id_menu_item_parent
 * @property string $link
 * @property string $label
 * @property integer $target
 * @property string $position
 * @property string $active
 *
 * The followings are the available model relations:
 * @property Menu $menu
 * @property MenuItem $parent
 * @property MenuItem[] $items
 * @property integer $itemCount
 */
class MenuItem extends CActiveRecord
{
	
	public $active = 1;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{menu_item}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		return array(
			array('label, id_menu, active, position, link', 'required'),
			array('id_menu_item_parent, target','default','setOnEmpty' => true, 'value' => null),
			array('link', 'PageSelectorValidator'),
			array('label', 'length', 'max'=>50),
			
		);
	}

	public function checkParams()
	{
		
		
		
		
		
	}
	
	public function defaultScope()
	{
		return array('alias' => 'menu_item',
						'order' => 'position ASC');
	}
	
	public function scopes()
	{
		return array(
			
				'active' => array('condition' => 'menu_item.active = 1'),
				'root' => array('condition' => 'id_menu_item_parent IS NULL'),
				
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'menu' => array(self::BELONGS_TO, 'Menu', 'id_menu'),
			'parent' => array(self::BELONGS_TO, 'MenuItem', 'id_menu_item_parent'),
			'items' =>  array(self::HAS_MANY, 'MenuItem', 'id_menu_item_parent'),
			'itemsCount' =>  array(self::STAT, 'MenuItem', 'id_menu_item_parent'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_menu' =>  Yii::t('menu', 'Parent Menu'),
			'label' => Yii::t('menu', 'Label'),
			'target' => Yii::t('menu', 'Target'),
			'position' => Yii::t('menu', 'Position (after...)'),
			'id_menu_item_parent' => Yii::t('menu', 'Parent Item'),
		);
	}
	

	
	
	public static function getListData($id_menu = null, $ignore = null)
	{
		
		if(empty($id_menu))
			return array();
		
		$criteria = '';
		$ids = array();
		
		if(!empty($ignore))
		{
			$root = MenuItem::model()->findByPk($ignore);
			$criteria = MenuItem::model()->getDbCriteria();
			
			if($root!=null)
			{
				$ids = $root->getHierarchyIds('down');
			}
		
		}
		
		if(!empty($id_menu))
			$menuC = ' AND id_menu = '.$id_menu;
		else 
			$menuC = '';
			
		$items = MenuItem::model()->findAll(array('condition' => 'id_menu_item_parent IS NULL'.$menuC));
		
		$list = array();
		
		foreach($items as $item)
		{
			self::getRecursiveList($item, $list, 0, $ids);
		}
		
		return $list;
	}
	
	private static function getRecursiveList($item, &$list, $level, $ignore)
	{
		
		if(in_array($item->id_menu_item,$ignore))
			return;
		
		$list[$item->id_menu_item] = str_repeat("- ", $level) . $item->label; 
		
		
		foreach($item->items as $item)
		{
			self::getRecursiveList($item, $list, $level+1, $ignore);
		}
		
	}
	
	public function getHierarchyIds($direction = 'down', &$ids = null)
	{
	
		if($ids === null)
			$ids = array($this->id_menu_item);
	
		if($direction === 'down')
			$items = $this->items;
		else
			$items = array($this->parent);
	
		foreach($items as $item)
		{
			$id = $item->id_menu_item;
			if(!(in_array($id, $ids)))
			{
				$ids[] = $id;
				$item->getHierarchyIds($direction, $ids);
			}
		}
	
		return $ids;
	}
	
	public static function getPositionListData($id_menu, $id_menu_item_parent, $id_menu_item = null)
	{
		
		if(empty($id_menu))
			return array();
		
		$criteria = MenuItem::model()->getDbCriteria();
		
		if(!empty($id_menu))
			$criteria->addColumnCondition(array('id_menu' => $id_menu));
		
		if(!empty($id_menu_item_parent))
			$criteria->addColumnCondition(array('id_menu_item_parent' => $id_menu_item_parent));
		else
			$criteria->addColumnCondition(array('id_menu_item_parent' => null));
		
		if($id_menu_item!=null)
		{
			$criteria->addCondition('id_menu_item <> :id_menu_item');
			$criteria->params = $criteria->params+array(":id_menu_item" => $id_menu_item);
		}
		
		$positions = array('1' => Yii::t('menu', 'First'));
		$positions = $positions + CHtml::listData(MenuItem::model()->findAll($criteria), function($data){ return $data->position+1; }, 'label');
		
		return $positions;
	}
	
	
	public static function getTargetListData()
	{
		return array(
			
				'_blank' => Yii::t('menu', 'New page'),
		);
	}
	
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('id_menu_item',$this->id_menu_item,true);
		$criteria->compare('id_menu',$this->id_menu,true);
		$criteria->compare('label',$this->label,true);
		
		if($this->id_menu_item_parent != null)
			$criteria->addColumnCondition(array('id_menu_item_parent' => $this->id_menu_item_parent));
		elseif($this->id_menu_item_parent !== 0)
			$criteria->addCondition('id_menu_item_parent IS NULL');
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}

}