<?php

/**
 * This is the model class for table "page".
 *
 * The followings are the available columns in table 'page':
 * @property string $id_page
 * @property string $id_author
 * @property string $lang
 * @property string $id_category
 * @property string $id_page_parent
 * @property string $title
 * @property string $alias
 * @property string $content
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $meta_robots
 * @property string $status
 * @property string $update_date
 * @property string $published_date
 * @property string $type
 * @property string $template
 * @property string $id_page_revision
 * 
 *
 * The followings are the available model relations:
 * @property Category $category
 * @property Lang $lang
 * @property Page $parent
 * @property Page[] $pages
 * @property PageAssoc[] $pageAssocs
 * @property BlogPage $blogPage
 * @property Blog $blog
 * @property Page $lasRevision
 */
class Page extends CActiveRecord
{
	
	const STATUS_UNPUBLISHED = 'unpublished';
	const STATUS_PUBLISHED = 'published';
	const STATUS_ARCHIVED = 'archived';
	const STATUS_TRASH = 'trash';
	const STATUS_INHERIT = 'inherit';
	
	const TYPE_CMS = 'cms';
	const TYPE_BLOG = 'blog';
	const TYPE_REVISION = 'revision';
	
	public $status = self::STATUS_UNPUBLISHED;
	public $type = self::TYPE_CMS;

	private $_oldValues;
	private $_savedRevision = false;
	private $_saveRevision = true;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function behaviors()
	{
		
		return array(
			'KeywordSearchable',
		);
		
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{page}}';
	}
	
	public function blog($id)
	{
		
		$this->getDbCriteria()->mergeWith(array(
			
				'alias' => 'page',
				'join' => 'INNER JOIN {{blog_page}} blog_page ON (blog_page.id_page = page.id_page)',
				'condition' => 'blog_page.id_blog = :id_blog',
				'params' => array(':id_blog' => $id),
				
		));
		
		return $this;
	}
	
	public function scopes()
	{
		return array(
			
				'published' => array('condition' => "status = '".self::STATUS_PUBLISHED."'"),
				
		);
	}
	
	public function beforeSave()
	{
		$this->update_date =  new CDbExpression("NOW()");
		
		if($this->status === self::STATUS_PUBLISHED && $this->published_date === null)
		{
			$this->published_date = new CDbExpression("NOW()");
		}
			
		return parent::beforeSave();
	}
	

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		return array(
				
			array('content', 'filter', 'filter' => array(new ImageCacheParser(), 'parse')),
			array('title, content, lang, alias, status', 'required', 'except' => 'draft'),
			array('meta_title, meta_keywords, meta_description, meta_robots, template', 'safe'),
			array('id_page_parent, id_category, published_date, template, meta_title, meta_keywords, meta_description','default', 'value' => null),
			array('title, alias', 'length', 'max'=>512),
			array('meta_keywords, meta_description', 'length', 'max'=>512),
			array('lang, meta_title, meta_keywords, meta_description, meta_robots', 'default' , 'value' => null),
			array('meta_title', 'length', 'max'=>256),
			
			array('alias','checkUniquePage'),
				
			array('id_page, title, type', 'safe', 'on'=>'search'),
		);
	}

	public function getChangeCheckAttributes()
	{
		return array('title', 'alias', 'content', 'meta_title', 'meta_keywords', 'meta_description', 'meta_robots');
	}
	
	public function setAttributes($values,$safeOnly=true)
	{
		if(!$this->isNewRecord)
		{
			$attributes = $this->getAttributes($this->getChangeCheckAttributes());
			
			if(array_intersect_key($values, $attributes) != $attributes)
				$this->_oldValues = $this->attributes;
		}
		parent::setAttributes($values);
	}
	
	public function afterSave()
	{
		parent::afterSave();
		
		if($this->saveRevision && $this->_oldValues !== null)
			$this->insertRevision($this->_oldValues);			
	}
	

	public function insertRevision($data)
	{
		
		if(!$this->isNewRecord)
		{
			$builder=$this->getCommandBuilder();
			$table=$this->getMetaData()->tableSchema;
		
			$data = CMap::mergeArray($this->attributes, $data);
			unset($data['id_page']);
			$data['id_page_revision'] = $this->id_page;
			$data['type'] = Page::TYPE_REVISION;
			$data['status'] = Page::STATUS_INHERIT;
					
			$command=$builder->createInsertCommand($table,$data);
			$command->execute();
			
			$this->hasSavedRevision = true;
			
			return $this->getDbConnection()->getLastInsertID();
		}
		
		return false;
			
	}
	
	public function setHasSavedRevision($value)
	{
		$this->_savedRevision = $value;
	}
	
	public function getHasSavedRevision()
	{
		return $this->_savedRevision;
	}
	
	public function setSaveRevision($value)
	{
		$this->_saveRevision = $value;
	}
	
	public function getSaveRevision()
	{
		return $this->_saveRevision;
	}
	
	public function checkUniquePage($attribute)
	{
		if($attribute === 'alias' && $this->type !== Page::TYPE_REVISION)
		{

			$params = array(
										':lang' => $this->lang,
										':alias' => $this->alias,
			);
			
			if($this->id_page_parent!=null)
				$params[':id_page_parent'] = $this->id_page_parent;
			
			if(!$this->isNewRecord)
				$params[':id_page'] = $this->id_page;

			$params['revision'] = Page::TYPE_REVISION;
			
			$exists = Page::model()->exists('lang = :lang AND alias = :alias AND type <> :revision AND '. (!$this->isNewRecord ? 'id_page <> :id_page AND ' : ' ') .  ($this->id_page_parent === null ? 'id_page_parent IS NULL' : 'id_page_parent = :id_page_parent'),
							$params);
			
			if($exists)
			{
				$this->addError('alias', Yii::t('page', 'The page alias must be unique for each language and parent pair'));
			}
			
		}	
	}
	
	public static function getSatusListData()
	{
		return array(
				self::STATUS_UNPUBLISHED => Yii::t('page', 'Unpublished'),
				self::STATUS_PUBLISHED => Yii::t('page', 'Published'),
				
		);
	}
	
	public static function getFilterStatusListData()
	{
		return array(
				'' => Yii::t('page', 'Published & Unpublished'),
				self::STATUS_PUBLISHED => Yii::t('page', 'Published'),
				self::STATUS_UNPUBLISHED => Yii::t('page', 'Unpublished'),
				self::STATUS_ARCHIVED => Yii::t('page', 'Archived'),
		);
	}

	public static function getFilterTypeListData()
	{
		return array(
				self::TYPE_CMS => self::mapTypeLabel(self::TYPE_CMS),
				self::TYPE_BLOG => self::mapTypeLabel(self::TYPE_BLOG),
		);
	}	
	
	public function getTypeLabel()
	{
		return self::mapTypeLabel($this->type);
	}
	
	public static function mapTypeLabel($type)
	{
		
		switch($type)
		{
			case self::TYPE_CMS:
				return Yii::t('page', 'Page');
			break;
			case self::TYPE_BLOG:
				return Yii::t('page', 'Blog');
			break;
		}
		
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'category' => array(self::BELONGS_TO, 'Category', 'id_category'),
			'language' => array(self::BELONGS_TO, 'Lang', 'lang'),
			'parent' => array(self::BELONGS_TO, 'Page', 'id_page_parent'),
			'pages' => array(self::HAS_MANY, 'Page', 'id_page_parent'),
			
			'pagesCount' => array(self::STAT, 'Page', 'id_page_parent', 'condition' => 'type <> :revision', 'params' => array('revision' => Page::TYPE_REVISION)),
			'pagesAssocsCount' => array(self::STAT, 'PageAssoc', 'id_page_from'),
			'pageAssocs' => array(self::HAS_MANY, 'PageAssoc', 'id_page_from',
					'index' => 'lang_to'
			),
				
			'extraOptions' => array(self::HAS_MANY, 'PageExtraOption', 'id_page',
					'index' => 'name'
			),
			
			'blogPage' => array(self::HAS_ONE, 'BlogPage', 'id_page'),
			'blog' => array(self::HAS_ONE, 'Blog', array('id_blog'=>'id_blog'), 'through'=>'blogPage') ,
			'author' => array(self::BELONGS_TO, 'Admin', 'id_author') ,
				
			//'revisions' => array(self::HAS_MANY, 'Page', array('id_page_revision'), 'order' => 'update_date DESC') ,
		);
	}
	
	public function getRevisions()
	{
		
		if($this->isNewRecord)
			return array();
		
		return Page::model()->findAll(array(
				'condition' => 'id_page = :id_page OR id_page_revision = :id_page',
				'params' => array('update_date' => $this->update_date,
						'id_page' => $this->id_page,
				),
				'order' => 'update_date DESC',
		));
	}

	public function getUnsavedRevision()
	{

		return Page::model()->find(array(
					'condition' => 'update_date > :update_date AND id_page_revision = :id_page',
					'params' => array('update_date' => $this->update_date,
							'id_page' => $this->id_page,
					),
					'order' => 'update_date DESC',
					'limit' => '1',
		));
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lang' => Yii::t('app', 'Language'),
			'id_category' => Yii::t('page', 'Category'),
			'id_page_parent' => Yii::t('page', 'Parent Page'),
			'title' => Yii::t('page', 'Title'),
			'alias' => Yii::t('page', 'Alias'),
			'content' => Yii::t('page', 'Content'),
			'meta_title' => Yii::t('page', 'Meta Title'),
			'meta_keywords' => Yii::t('page', 'Meta Keywords'),
			'meta_description' => Yii::t('page', 'Meta Description'),
			'meta_robots' => Yii::t('page', 'Meta Robots'),
			'status' => Yii::t('page', 'Status'),
			'type' => Yii::t('page', 'Type'),
			'template' => Yii::t('page', 'Template'),
			'update_date'  => Yii::t('page', 'Update Date'),
			'published_date'  => Yii::t('page', 'Published Date'),
		);
	}

	public function getMetaTitle()
	{
		return $this->meta_title != null ? $this->meta_title : $this->title;
	}
	
	public function getMetaDescription()
	{
		if(!empty($this->meta_description))
		{
			return $this->meta_description;
		}
		else
		{
			$description = strip_tags(html_entity_decode($this->excerpt));
			$description = str_replace(array("\n","\r"), "", $description);
			
			return $description;
		}
	}
	
	public function getExcerpt()
	{
		$readMore = strpos($this->content, "<div id=\"system-readmore\">&nbsp;</div>");

		//found a readmore
		if($readMore !== false)
		{
			return substr($this->content,0,$readMore);
		}
		
		$firstParagraph = strpos($this->content,"</p>");
		
		if($firstParagraph != false)
		{
			 return substr($this->content,0,$firstParagraph+4);
		}
		
		//just return the whole stuff.
		return $this->content;
	}
	
	public function getAllPagesIds()
	{
		
		$items = array();
		
		foreach($this->pages as $page)
		{
			$items[] = $page->id_page;
			$items = array_merge($items, $page->getAllPagesIds());
		}
		return $items;
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
	

		$criteria=new CDbCriteria;
		
		if(empty($this->status))
		{
			$criteria->compare('status','<>trash',false);
			$criteria->compare('status','<>archived',false);
		}
		elseif($this->status === 'all')
		{
			$criteria->compare('status','',false);
		}
		else
			$criteria->compare('status',$this->status,false);
		


		$criteria->compare('id_page',$this->id_page,false);
		$criteria->compare('title',$this->title,true);
		
		
		if($this->id_page_parent != null)
			$criteria->addColumnCondition(array('id_page_parent' => $this->id_page_parent));
		elseif($this->id_page_parent !== 0)
			$criteria->addCondition('id_page_parent IS NULL');

		if($this->lang!=null)
			$criteria->addColumnCondition(array('lang' => $this->lang));
				
		if($this->id_category!=null)
			$criteria->addColumnCondition(array('id_category' => $this->id_category));
		
		if($this->type!=null)
			$criteria->addColumnCondition(array('type' => $this->type));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => Yii::app()->params->pageSize)
		));
	}

}