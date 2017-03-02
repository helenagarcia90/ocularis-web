<?php
	Yii::app()->clientScript->registerScript('slider', '

			function matchCarouselHeight()
			{
				 var wH = $(window).height();
    			$(".carousel-inner .item").css("height", wH);
			}

			$(window).resize(function() {
			    matchCarouselHeight();
			});

			matchCarouselHeight();


			$(".carousel").carousel({
			  interval: '.$slider->animpause.'
			})


			 $(window).scroll(function(){

				if($(window).scrollTop() > $(window).height())
			  	{
			  		$("#topNav").addClass("navbar-fixed-top");
                    $("#ventajas").css({
                        "margin-top": $("#topNav").height() + "px"
                    });
			  	} else
			  	{
                    $("#ventajas").css({
                        "margin-top": "0px"
                    });
			  		$("#topNav").removeClass("navbar-fixed-top");
			  	}

			});


			  $("#toNav").on("click", function(e){
					e.preventDefault();
			  		$("body").scrollTo("#topNav",{duration: "slow"});

				});


			');

	$duration = $slider->animduration/1000;

	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.scrollTo.min.js');


	Yii::app()->clientScript->registerCss('slider', '

			.carousel-inner > .item {
				    -webkit-transition: '.$duration.'s ease-in-out left;
				    -o-transition: '.$duration.'s ease-in-out left;
				    transition: '.$duration.'s ease-in-out left;
				}

			');

?>

<div id="carousel" class="carousel slide" data-ride="carousel">


 <div class="down">
 	<a href="#" id="toNav">
 		<span class="fa fa-angle-double-down">
		</span>
	</a>
 </div>


  <div class="carousel-inner">

  <?php

  $effect = array(
  	0 => 'fall',
	1 => 'zoomin',
	2 => 'zoomout',
  );

  ?>

   <?php foreach($slider->slides(array('scopes' => 'active')) as $index => $slide): ?>
    <?php //$img = Yii::app()->imageCache->createUrl('slider',$slide->image); ?>
    <div class="item <?=$index==0 ? ' active' : ''?>" style="background-image: url('<?=Yii::app()->imageCache->createUrl('slider',$slide->image);?>');">

	      	<div class="table-content">
		      <div class="carousel-caption">
		      	<div class="cell">
		      		<div class="container">
		      			<div class="img <?=$effect[$index%3]?>">
		        			<?=CHtml::image(Yii::app()->baseUrl.'/medias/images/logo-blanco.png', 'Ocularis', array('class' => 'img-responsive'))?>
		        		</div>
		        	</div>
		        </div>
		      </div>
		     </div>

    </div>
    <?php endforeach;?>
  </div>

  <a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
</div>