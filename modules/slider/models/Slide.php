<?php

/**
 * @property string $id_slider_slide
 * @property string $id_slider
 * @property string $image
 * @property string $legend
 * @property string $link
 * @property string $position
 * @property string $active
 * @property CUploadedFile $image_file
 */

class Slide extends CActiveRecord
{

	public $active = 1;
	public $image_file;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{slider_slide}}';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
	
		return array(
				array('image, position, active', 'required'),
				array('link, legend', 'safe'),
		);
	}
	
	public function scopes()
	{
		return array(
			
				'active' => array(
							'condition' => 'active = 1'
					),
				
		);
	}
	
	public static function getDirName()
	{
		return dirname(Yii::app()->basePath).DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."sliders";
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
	
		return array(
				'slider' => array(self::BELONGS_TO, 'Slider', 'id_slider'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id_slider_slide' => 'ID',
				'id_slider' => Yii::t('SliderModule.main','Slider'),
				'image' => Yii::t('SliderModule.main','Image'),
				'legend' => Yii::t('SliderModule.main','Legend'),
				'link' => Yii::t('SliderModule.main','Link'),
				'position' => Yii::t('SliderModule.main','Position'),
				'active' => Yii::t('SliderModule.main','State'),
		);
	}
	
	public function search()
	{
	
		$criteria=new CDbCriteria;
		$criteria->compare('id_slider_slide',$this->id_slider,true);
		$criteria->compare('id_slider',$this->id_slider,true);
		$criteria->compare('legend',$this->legend,true);
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	public function delete()
	{
		if(parent::delete())
		{
			@unlink(self::getDirName().DIRECTORY_SEPARATOR.$this->image);
		}
	}
	
	
}