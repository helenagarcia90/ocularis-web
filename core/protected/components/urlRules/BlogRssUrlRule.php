<?php



class BlogRssUrlRule extends PatternUrlRule
{

	public $urlSuffix = '.xml';
	public $template = 'feed/<alias>';

	public $route = 'blog/rss';
	
	
	public function parseTokens($values)
	{
		
				//create the command
				$command = Yii::app()->db->createCommand()
							->select('p.id_blog') //only select the id
							->from('{{blog}} p');
				
				if(isset($values['alias']))
				{
					$command->andWhere("p.alias = :alias",
						array(':alias' => $values['alias'])); //select the page alias
				}
				
				if(isset($values['id']))
				{
					$command->andWhere("p.id_page = :id_page",
						array(':id_page' => $values['id'])); //select the page id
				}
				
				
				$command->andWhere("p.lang = :lang",array(":lang" => $values['lang']));
				
				//get the id
				$id = $command->queryScalar();
		
				// false == not found
				if($id !== false)
				{
					$_GET['id'] = $id;
					return true;
				}
		
		
		return false;
	}
	
	
	public function getTokenValues(&$params)
	{
		
			
			if (isset($params['id']))
			{
			
				$blog = Blog::model()->findByPk($params['id']); //find the page for given id
				
			
				if($blog === null)
					return false;
				else
				{
					
					//We are asking for a translation
					if(isset($params['lang']) && $params['lang'] !== Yii::app()->language)
					{
						throw new NoTranslationException(); //this is for language switcher t know that the translation does not exist
					}
					
					$values = array();
					
					$values['id'] = $blog->id_blog;
					$values['alias'] = $blog->alias;
					$values['lang'] = $blog->lang;
					
					unset($params['id']);
			
					return $values;
			
				}
			
			
			}
			
			return false;
		
		
	}
	
}