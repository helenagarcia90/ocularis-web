<?php

class Item extends CComponent
{
	
	
	public $title;
	public $date;
	public $content;
	public $link;
	
	public function getExerpt()
	{
		
		$exerpt = strip_tags($this->content);
		$exerpt = mb_substr($exerpt,0,180, 'UTF-8');
				
		return $exerpt . "&hellip;";
		
	}
	
}