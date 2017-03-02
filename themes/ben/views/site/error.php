<div class="jumbotron">
<h1><span class="label label-danger"><?=$error['code']?></span> Oooop! <span class="fa fa-chain-broken"></span></h1>
<br/>
<p class="alert alert-warning">
That was not supposed to happen. You might have followed a bad link or this page does not exist anymore.
You have the choice between the following options:
</p>
<br/>
<p class="text-center">
	<?= CHtml::link('<span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;&nbsp;Go to the home page', array('/site/index'), array('class' => 'btn btn-lg btn-primary'))?></li>
</p>
<br/>
<p>
		<form action="<?=$this->createUrl('/page/search')?>" method="GET">
			<div class="input-group input-group-lg">
			  <input type="text" class="form-control"  name="keywords" placeholder="Make a research">
			  <span class="input-group-btn"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button></span>
			</div>
		</form>
</p>
<br/>

<?php
					$lasts = Page::model()->findAll(
						array(
								'condition' => 'type = :type',
								'join' => 'JOIN {{blog_page}} pb ON (t.id_page = pb.id_page  AND pb.id_blog = :id_blog)',
								'limit' => 5,
								'order' => 'published_date DESC',
								'params' => array('type' => Page::TYPE_BLOG, ':id_blog' => 1),
								)
					);
 
				?>
				
				<h2>Visit one of my latest posts</h2>
				<ul id="lasts" class="list-unstyled">
				<?php foreach($lasts as $page):?>
					<li><?=CHtml::link($page->title, array('/page/index', 'id' => $page->id_page))?></li>
				<?php endforeach;?>
				</ul>
</div>