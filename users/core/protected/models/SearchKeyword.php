<?php

/**
 * This is the model class for table "search_keyword".
 *
 * The followings are the available columns in table 'menu':
 * @property string $id_keyword
 * @property string $lang
 * @property string $keyword
 
 
 * The followings are the available model relations:
 * @property Lang $language
 * @property SearchKeywordMatch[] $blogPages
 */
class SearchKeyword extends CActiveRecord
{
		
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{search_keyword}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		return array(
			array('lang, keyword', 'required'),
			array('keyword', 'length', 'max'=>32),
			
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{

		return array(
			
			'matches' => array(self::HAS_MANY, 'SearchKeywordMatch', 'id_keyword'),
			'language' => array(self::BELONGS_TO, 'Lang', 'lang'),
		);
	}
	
	public static function extractWords($text)
	{
		preg_match_all("/([0-9a-zA-Z-%$]|\xC3[\x80-\x96\x98-\xB6\xB8-\xBF]|\xC5[\x92\x93\xA0\xA1\xB8\xBD\xBE]){3,}/",strip_tags(html_entity_decode($text, ENT_COMPAT | ENT_HTML401, 'UTF-8')),$matches);
		
		if(count($matches[0])>0)
			return array_map('mb_strtolower', $matches[0], array_fill(0, count($matches[0]),'UTF-8'));
		else
			return array();
		
	}
	
	public static function index($content, $lang, $type, $id)
	{
		
		Yii::beginProfile("application.models.SearchKeyword.index.{$lang}.{$type}.{$id}", 'application.models.SearchKeyword');
		
		$keywords = array_count_values(self::extractWords($content));
		
		$transaction = null;
		if(Yii::app()->db->currentTransaction === null)
		{
			
			$transaction = Yii::app()->db->beginTransaction();
		}
		
		try{
			
			Yii::beginProfile("application.models.SearchKeyword.prepare.{$lang}.{$type}.{$id}",'application.models.SearchKeyword');
			SearchKeywordMatch::model()->deleteAllByAttributes(array('type' => $type, 'id' => $id));
			
			$criteria = SearchKeyword::model()->getDbCriteria();
			$criteria->addInCondition('keyword', array_keys($keywords));
			$criteria->index = 'keyword';
			$criteria->addColumnCondition(array('lang' => $lang));
			
			$keywordModels = SearchKeyword::model()->findAll($criteria);
			
			$keyword_data = array();
			$match_data = array();
			Yii::endProfile("application.models.SearchKeyword.prepare.{$lang}.{$type}.{$id}",'application.models.SearchKeyword');
			
			$i = 0;
			foreach($keywords as $keyword => $count)
			{
				if(strlen($keyword)>32)
					continue; //do not index "words" of more than 32 chars (which is the db limit)
				
				
				if(!isset($keywordModels[$keyword]))
				{
					
					$keyword_data[] = array(
									'lang' => $lang,
									'keyword' => $keyword,
					);
					
				}
								
				$match_data[] = array(
						'id_keyword' => isset($keywordModels[$keyword]) ? $keywordModels[$keyword]->id_keyword  : 
											new CDbExpression("(SELECT id_keyword FROM {{search_keyword}} WHERE keyword = :keyword_{$i} AND lang = :lang)", array(':keyword_'.$i => (string)$keyword, ':lang' => $lang)),
						'type' => $type,
						'id' => $id,
						'count' => $count
				);
				
				$i++;
			}
			
			Yii::beginProfile("application.models.SearchKeyword.inserts.{$lang}.{$type}.{$id}",'application.models.SearchKeyword');
			
			$builder=Yii::app()->db->schema->commandBuilder;

			

			if(count($keyword_data)>0)
			{
				$command=$builder->createMultipleInsertCommand(SearchKeyword::model()->tableName(), $keyword_data);
				$command->execute();
			}
			
			$command=$builder->createMultipleInsertCommand(SearchKeywordMatch::model()->tableName(), $match_data);
			$command->execute();
			
			Yii::endProfile("application.models.SearchKeyword.inserts.{$lang}.{$type}.{$id}",'application.models.SearchKeyword');
			
			if($transaction !== null)
				$transaction->commit();
			
					
		}
		catch(Exception $e)
		{
			if($transaction !== null)
				$transaction->rollback();
			
			throw $e;
		}
		
		Yii::endProfile("application.models.SearchKeyword.index.{$lang}.{$type}.{$id}",'application.models.SearchKeyword');
		
	}

}