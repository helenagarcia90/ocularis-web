<?php

abstract class PatternUrlRule extends CBaseUrlRule {
	
	/**
	 *  @var string The template for the url
	 * 
	 * */
	
	const REGEX_ALIAS = '\w[\w\-\._~]*'; //start with a word, can contain words, ".", "-", "_", and "-"
	const REGEX_ID = '\d+'; //must be a number
			
	public $urlSuffix;
	
	/**
	 * @var string the route where to point
	 */
	public $route;
	
	/**
	 * @var string The template used for url parsing and creation
	 */
	public $template;
	
	/**
	 * @var boolean whether the URL rule uses language
	 */
	public $usesLang = true;
	
	/**
	 * Set a default value for some tokens
	 * This will force some tokens to have a velue in order for the rule to be valid
	 * @var array
	 */
	
	public $defaultTokens = array();
	
	/**
	 * @var array the possible tokens with their regular expressions
	 */
	protected $references = array();
		
	/**
	 * @var array Default tokens
	 */
	private $_r = array(
							'alias' => self::REGEX_ALIAS,
							'id' => self::REGEX_ID,
						);
	

	protected $tokens = array();
	protected $pattern;
	protected $parsedTemplate;
	protected $parsedTokens = array();
	protected $append = false;
	
	
	public function init($manager)
	{

		Yii::beginProfile('application.components.PatternUrlRule.init','application.components.PatternUrlRule');
		
		
		if($this->usesLang && Yii::app()->languageManager->addLangInUrl && strpos($this->template,"<lang>") === false )
		{
			if(!strncasecmp($this->template,'http://',7) || !strncasecmp($this->template,'https://',8))
				throw new CException("The template {$this->template} for route {$this->route} is not valid. It must contain the language token");
			
			$this->template = $manager->langPrefix."/".$this->template;
		}
		
		$this->hasHostInfo=!strncasecmp($this->template,'http://',7) || !strncasecmp($this->template,'https://',8);
	
			preg_match_all ( '/<(\?)?([a-z][a-z\_]+)([\+\?\*]?)>/', $this->template, $matches );
		
			$ignore = $matches[1];
			$tokens = $matches[2];
			$modifiers = $matches[3];
		
		
			foreach ( $tokens as $index => $name ) {
		
				$this->tokens[$name] = array(	'name' => $name,
												'modifier' => $modifiers[$index]);
				
				if($ignore[$index] === '')
					$this->parsedTokens[$name] = $name;  
				
				if(isset($this->references[$name]))
					$regex = $this->references[$name];
				elseif(isset($this->_r[$name]))
					$regex = $this->_r[$name];
				elseif($name === 'lang')
					$regex = Yii::app()->languageManager->regex;
				else
					$regex = self::REGEX_ALIAS; 
				
				
				if($modifiers[$index] === '*' || $modifiers[$index] === '+')
				{
					$regex = "(?:{$regex}\/)*(?:{$regex})";
				}
				
				if($modifiers[$index] === '*' || $modifiers[$index] === '?')
				{
					$tr ["<{$name}>/"] = "(?:(?P<$name>$regex)\/)?";
				}
				else
				{
					$tr["<$name>"] = "(?P<$name>$regex)";
				}
				
				
				
			}
				
			$tr['/'] = '\\/';
			$tr['.'] = '\\.';
			$tr[':'] = '\\:';
		
			$this->parsedTemplate = preg_replace("/<(?:\?)?([a-z][a-z\_]+)(?:[\+\?\*]?)>/", "<$1>", $this->template);
			$this->pattern = '/^' . strtr($this->parsedTemplate, $tr);
			
			$p=rtrim($this->template,'*');
			$this->append=$p!==$this->template;
			$p=trim($p,'/');

			if($this->append)
				$this->pattern .= '/u';
			else
				$this->pattern .= '$/u';

		
		
		Yii::endProfile('application.components.PatternUrlRule.init','application.components.PatternUrlRule');
		
	}

	
	public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
	{
		
		
		 
		if($this->route === null)
			return false;
			
		if($this->hasHostInfo)
		{
			$pathInfo=strtolower($request->getHostInfo()).rtrim('/'.$pathInfo,'/');
		}
		
		if($this->urlSuffix!==null)
			$pathInfo=$manager->removeUrlSuffix($rawPathInfo,$this->urlSuffix);
		
		if(preg_match($this->pattern, $pathInfo, $matches))
		{
			
			$tokens = array_intersect_key($matches, $this->parsedTokens);
			$tokens['lang'] = isset($matches['lang']) ? $matches['lang'] : Yii::app()->language; 
			
			//force some token values
			foreach($this->defaultTokens as $key => $value)
			{
				$tokens[$key] = $value;
			}
			
			if(!$this->parseTokens($tokens))
				return false;
			
			$_GET['lang'] = $tokens['lang'];
		
			if($pathInfo!==$matches[0])
				$manager->parsePathInfo(ltrim(substr($pathInfo,strlen($matches[0])),'/'));
			
			return $this->route;
			
		}

		
		
		return false;
	}
	
	public function createUrl($manager, $route, $params, $ampersand)
	{
		
		
		if($this->route === $route)
		{
		
			if( ($values = $this->getTokenValues($params)) === false)
				return false;


			//check that all the defaultTokens match the results
			foreach($this->defaultTokens as $key => $value)
			{
				if( !isset($values[$key]) || $values[$key] !== $value )
					return false;
			}
			
			$tr = array();
			foreach($this->tokens as $key => $value)
			{
				if(in_array($this->tokens[$key]['modifier'], array('*', '?')))
					$tr["<$key>/"] = $values[$key];
				else
					$tr["<$key>"] = $values[$key];
			}
			
			if($this->usesLang && Yii::app()->languageManager->addLangInUrl && isset($params['lang']) && !isset($values['lang']))
				$tr["<lang>"] = $params['lang'];
			
			unset($params['lang']);
			
			$url = strtr($this->parsedTemplate, $tr);
			
			$suffix=$this->urlSuffix===null ? $manager->urlSuffix : $this->urlSuffix;
			
			if($this->hasHostInfo)
			{
				$hostInfo=Yii::app()->getRequest()->getHostInfo();
				if(stripos($url,$hostInfo)===0)
					$url=substr($url,strlen($hostInfo));
			}
			
		
			if(empty($params))
				return $url.$suffix;
				
			
			if($this->append)
				$url.='/'.$manager->createPathInfo($params,'/','/').$suffix;
			else
			{
				if($url!=='')
					$url.=$suffix;
				$url.='?'.$manager->createPathInfo($params,'=',$ampersand);
			}
			
			
			
			return $url;
		
		}
		
		return false;
	}
	
	/**
	 * Resolves the template parts values given the params from createUrl
	 * @param array() $params
	 */
	
	public abstract function getTokenValues(&$params);
	
	/**
	 * Decodes the template and checks if it matches the current UrlRule
	 * @param array() $tokens
	 */
	
	public abstract function parseTokens($tokens);

	
}
