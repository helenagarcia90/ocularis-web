<?php


class Slider extends CActiveRecord
{
	
	/**
	 * @property integer $id_slider
	 * @property string $name
	 * @property string $anchor
	 * @property string $lang
	 * @property integer $animduration
	 * @property integer $animpause
	 * @property Slide $slides[] 
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
		return '{{slider}}';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
	
		return array(
				array('name, anchor, lang, animduration, animpause', 'required'),
				array('anchor','checkUniqueSlider'),
		);
	}
	
	public function checkUniqueSlider($attribute)
	{
		if($attribute === 'anchor')
		{
	
			$params = array(
					':lang' => $this->lang,
					':anchor' => $this->anchor,
			);
	
			if(!$this->isNewRecord)
				$params[':id_slider'] = $this->id_slider;
	
			$exists = Slider::model()->exists('lang = :lang AND anchor = :anchor'. (!$this->isNewRecord ? ' AND id_slider <> :id_slider' : ' '),
					$params);
	
			if($exists)
			{
				$this->addError('alias', Yii::t('SliderModule.main', 'The anchor must be unique for each language'));
			}
	
		}
	}
	
	public static function getDataList()
	{
		return CHtml::listData(self::model()->findAll(), 'id_slider', 'name', 'language.name');
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
	
		return array(
				'language' => array(self::BELONGS_TO, 'Lang', 'lang'),
				'slides' => array(self::HAS_MANY, 'Slide', 'id_slider', 
									'order' => 'position ASC'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id_slider' => 'ID',
				'lang' => Yii::t('app','Language'),
				'name' => Yii::t('SliderModule.main','Name'),
				'anchor' => Yii::t('SliderModule.main','Anchor'),
				'animduration' => Yii::t('SliderModule.main','Animation Duration'),
				'animpause' => Yii::t('SliderModule.main','Pause'),
		);
	}
	
	public function search()
	{
	
		$criteria=new CDbCriteria;
		$criteria->compare('id_slider',$this->id_slider,true);
		$criteria->compare('anchor',$this->anchor,true);
		$criteria->compare('lang',$this->lang,true);
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	
}