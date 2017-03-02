<?php

class RssReaderWidget extends CWidget{

	public $limit = 5;
	public $url;
	private $_dom;
	private $_items = array();
	
	public function init()
	{
		
		Yii::import('modules.rssReader.models.Item');
		

		$this->_items = Yii::app()->cache->get($this->getCacheId());
		
		if($this->_items === false)
		{
			try {

				$dom = new DomDocument();
				$dom->load($this->url);
				$itms = $dom->getElementsByTagName("item");
			
			
				$c = 0;
				foreach($itms as $item)
				{
					$i = new Item();
					$i->title = $item->getElementsByTagName("title")->item(0)->nodeValue;
					$i->date = strtotime($item->getElementsByTagName("pubDate")->item(0)->nodeValue) ;
					$i->content = $item->getElementsByTagName("description")->item(0)->nodeValue;
					$i->link = $item->getElementsByTagName("link")->item(0)->nodeValue;
								
					$this->_items[] = $i;
				
					$c++;
					if($c == $this->limit)
						break;
				}
			
				Yii::app()->cache->set($this->getCacheId(), $this->_items, 3600);

			} catch(Exception $e) {
				//fuck it!!
				$this->_items = array();
			}

		}
		
	}

	private function getCacheId()
	{
		return __CLASS__.'-'.$this->url . '-'.$this->limit;
	}
	
	public function run()
	{


		$this->render('rssFeed',array(
				'items' => $this->_items,
		));

	}



}
