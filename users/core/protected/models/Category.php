<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property string $id_category
* @property string $id_category_parent
 * @property string $name
 * @property string $level
 * @property string $left
 * @property string $right
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Category $parent
 * @property Category[] $categories
 * @property Page[] $pages
 * @property integer $pageCount
 */
class Category extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Category the static model class
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
		return '{{category}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('description', 'safe'),
			array('name', 'length', 'max'=>512),
			array('id_category_parent', 'default', 'value'=>null),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_category, id_category_parent, name, description', 'safe', 'on'=>'search'),
		);
	}

	public static function getListData($ignore = null)
	{
		
		$criteria = new CDbCriteria();
		
		if(!$ignore !== null)
		{
			$category = Category::model()->findByPk($ignore);
			
			if($category !== null)
			{
				$criteria->addCondition('`left` NOT BETWEEN :left AND :right');
				$criteria->params = array(
						'left' => $category->left,
						'right' => $category->right,
				);
			}
		}
		
		$criteria->order = '`left` ASC';
		
		$list = array();
		
		foreach( Category::model()->findAll($criteria) as $category)
		{
			$list[$category->id_category] = str_repeat('&nbsp;', $category->level*5) . $category->name;
		}
		
		return $list;
	}

	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'pages' => array(self::HAS_MANY, 'Page', 'id_category'),
			'pageCount' => array(self::STAT, 'Page', 'id_category'),
			'parent' => array(self::BELONGS_TO, 'Category', 'id_category_parent'),
			'categories' => array(self::HAS_MANY, 'Category', 'id_category_parent'),
			'categoriesCount' => array(self::STAT, 'Category', 'id_category_parent'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_category' => 'Id',
			'id_category_parent' => Yii::t('page', 'Parent Category'),
			'name' => Yii::t('page', 'Name'),
			'description' => Yii::t('page', 'Description'),
		);
	}

	public static function rebuildHierarchy($category = null, &$left = 1, $level = 0)
	{
		
		if($category === null)
			$categories = Category::model()->findAllByAttributes(array('id_category_parent' => null));
		else
			$categories = $category->categories;

		foreach($categories as $category)
		{
			$category->left = $left++;
			$category->level = $level;
			Category::rebuildHierarchy($category, $left, $level+1);
			$category->right = $left++;
			$category->save();		
		}
		
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		if($this->id_category_parent != null)
			$criteria->addColumnCondition(array('id_category_parent' => $this->id_category_parent));
		else
			$criteria->addCondition('id_category_parent IS NULL');
		
		$criteria->compare('id_category',$this->id_category,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}