<?php



class CmsPageUrlRule extends PatternUrlRule
{


	public $template = '<parent*>/<alias>';

	public $route = 'page/index';
	
	public $references = array(
		
			'parent' => self::REGEX_ALIAS,
			
	);
	
	public function parseTokens($values)
	{
		
				//create the command
				$command = Yii::app()->db->createCommand()
							->select('p.id_page') //only select the id
							->from('{{page}} p')
							->where("p.type=:type AND p.status = :status",
									array(	':type' => Page::TYPE_CMS,
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
				
				//parent segment is present
				if(isset($values['parent']) && $values['parent'] !== '')
				{
					
					$parents = explode('/', $values['parent']);

					$count = count($parents);
					
					$i = 0;
					//now, for each segment, from right to left, check that the segment is a valid alias of the page parent (recursively)
					while( ($alias = array_pop($parents)) !== null)
					{
						$i++;
					
						if($i === 1)
							$command->join("{{page}} p{$i}", "p{$i}.id_page = p.id_page_parent AND p$i.alias = :p{$i}alias",array(":p{$i}alias" =>  $alias));
						else
						{
							$prev = $i-1;
							$command->join("{{page}} p{$i}", "p{$i}.id_page = p{$prev}.id_page_parent AND p$i.alias = :p{$i}alias",array(":p{$i}alias" =>  $alias));
						}
					
					}
					
					if($this->tokens['parent']['modifier'] === '*') //need all parent. The last one must not have parent
						$command->andWhere("p{$i}.id_page_parent IS NULL");

				}
				elseif(isset($values['parent'])) //only check that parent is null if the parent segment is present and empty (= no parent)
				{
					$command->andWhere("p.id_page_parent IS NULL");
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
			
				$page = Page::model()->findByAttributes(array('id_page' => $params['id'], 'type' => Page::TYPE_CMS, 'status' => Page::STATUS_PUBLISHED )); //find the page for given id
			}
			else if (isset($params['alias']))
			{
				$page = Page::model()->findByAttributes(array('alias' => $params['alias'], 'lang' => (isset($params['lang']) ? $params['lang'] : Yii::app()->languageManager->defaultLang ), 'type' => Page::TYPE_CMS, 'status' => Page::STATUS_PUBLISHED )); //find the page for given alias
			}
			else
				return false;
				
				if($page === null)
					return false;

				
				//We are asking for a translation
				if(isset($params['lang']) && $params['lang'] !== Yii::app()->language)
				{
					if(isset($page->pageAssocs[$params['lang']]))
					{
						$page = $page->pageAssocs[$params['lang']]->pageTo;
					}
					else
					{
						throw new NoTranslationException(); //this is for language switcher t know that the translation does not exist
					}
				}
					
				
				if($page !== null)
				{
					
						
					$values = array();

					$values['id'] = $page->id_page;
					$values['alias'] = $page->alias;
					$values['lang'] = $page->lang;
					

					$parent = '';
						
					$i=0;
					while( ($page = $page->parent) !=null )
					{
						$parent = $page->alias . "/" . $parent;
						$i++;
						if($i>100)
							throw new CException("Too many loops. This might be an infinite loop. it has stopped");
					}
						
					$values['parent'] = $parent;
				
					unset($params['id']);
					unset($params['alias']);
					unset($params['lang']);
			
					return $values;
			
				}
			
			
			return false;
		
		
	}
	
}