<?php



class BlogPageUrlRule extends PatternUrlRule
{

	public $template = '<blog>/<year>/<month>/<alias>';

	public $route = 'page/index';
	
	public $references = array(

			'year' => '\d{4}',
			'month' => '\d{2}',
			'day' => '\d{2}',
			'blog' => self::REGEX_ALIAS,
			
	);
	
	public function parseTokens($values)
	{
		
				//create the command
				$command = Yii::app()->db->createCommand()
							->select('p.id_page') //only select the id
							->from('{{page}} p')
							->where("p.type=:type AND p.status = :status",
									array(	':type' => Page::TYPE_BLOG, //of type cms
											':status' => Page::STATUS_PUBLISHED)); //published
				
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
				
				if(isset($values['blog']))
				{
					
					$command->join("{{blog_page}} bp", "bp.id_page = p.id_page");
					$command->join("{{blog}} b", "b.id_blog = bp.id_blog AND b.alias= :blog_alias AND b.lang = p.lang",
							array(':blog_alias' => $values['blog']));
					
				}
				
				if(isset($values['year']))
				{
					$command->andWhere("YEAR(published_date) = :year",
							array(':year' => $values['year'])
					);
				}
				
				if(isset($values['month']))
				{
					$command->andWhere("MONTH(published_date) = :month",
							array(':month' => $values['month'])
					);
				}
				
				if(isset($values['day']))
				{
					$command->andWhere("DAY(published_date) = :day",
							array(':day' => $values['day'])
					);
				}
				
				$command->andWhere("p.lang = :lang",array(":lang" => $values['lang']));
				
				//get the page id
				$pageId = $command->queryScalar();
		
				// false == not found
				if($pageId !== false)
				{
					$_GET['id'] = $pageId;
					return true;
				}
		
		
		return false;
	}
	
	
	public function getTokenValues(&$params)
	{
		
			
			if (isset($params['id']))
			{
			
				$page = Page::model()->with('blog')->findByAttributes(array('id_page' => $params['id'], 'type' => Page::TYPE_BLOG, 'status' => Page::STATUS_PUBLISHED )); //find the page for given id
			
				if($page === null)
					return false;

				//We are asking for a translation
				if(isset($params['lang']) && $params['lang'] !== Yii::app()->language)
				{
					throw new NoTranslationException(); //this is for language switcher t know that the translation does not exist
				}
				
				if($page !== null)
				{
					
						
					$values = array();

					$values['id'] = $page->id_page;
					$values['alias'] = $page->alias;
					$values['lang'] = $page->lang;
					$values['blog'] = $page->blog->alias;
					$values['year'] = substr($page->published_date,0,4);
					$values['month'] = substr($page->published_date,5,2);
					$values['day'] = substr($page->published_date,8,2);
					
					unset($params['id']);
			
					return $values;
			
				}
			
			
			}
			
			return false;
		
		
	}
	
}