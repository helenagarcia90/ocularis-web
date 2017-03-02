<?php

class KeywordSearchable extends CActiveRecordBehavior
{
	
	
	public function searchByKeywords($keywords)
	{ 
		
		$model = $this->owner;
		$lang = Yii::app()->language;
		
		$keywords = SearchKeyword::extractWords($keywords);
	
		$c = $model->getDbCriteria();
		
		$c->mergeWith(
		
					array(
						
						'join' => "INNER JOIN {{search_keyword_match}} search_keyword_match ON (search_keyword_match.id = {$model->getTableAlias()}.{$model->getMetaData()->tableSchema->primaryKey} AND search_keyword_match.type = :type) " .
									"INNER JOIN {{search_keyword}} search_keyword ON (search_keyword.id_keyword = search_keyword_match.id_keyword)"
						,
						'params' => array(':type' => get_class($model)),
						'group' => "{$model->getTableAlias()}.{$model->getMetaData()->tableSchema->primaryKey}",
						'order' => 'SUM(search_keyword_match.count) DESC',
						
					)
		);
		
		$c->addInCondition('search_keyword.keyword',$keywords);
		$c->addColumnCondition(array('search_keyword.lang' => $lang));
		
		return $model;
		
	}
	
}