<?php

class BasicPager extends CLinkPager
{
	
	public $htmlOptions = array();
	
	public function run()
	{
		$buttons=$this->createPageButtons();
		if(empty($buttons))
			return;
	
		echo CHtml::tag('ul',$this->htmlOptions,implode("\n",$buttons));
	
	}
	
	public function createPageButtons()
	{
	
		if(($pageCount=$this->getPageCount())<=1)
			return array();
	
		$currentPage=$this->getCurrentPage(false); // currentPage is calculated in getPageRange()
		$buttons=array();
	
		// prev page
		if(($page=$currentPage-1)<0)
			$page=0;
	
		if($currentPage>0)
			$buttons[]= '<li class="previous">'.CHtml::link(Yii::t('yii','&lt; Previous'),$this->createPageUrl($page)).'</li>';
	
		list($beginPage,$endPage)=$this->getPageRange();
		// internal pages
		for($i=$beginPage;$i<=$endPage;++$i)
			$buttons[]=$this->createPageButton($i+1,$i,'page',false,$i==$currentPage);
		
		// next page
		if(($page=$currentPage+1)>=$pageCount-1)
			$page=$pageCount-1;
	
		if($currentPage<$pageCount-1)
			$buttons[]= '<li class="next">'.CHtml::link(Yii::t('yii','Next &gt;'),$this->createPageUrl($page)).'</li>';
	
		return $buttons;
	}
	
}