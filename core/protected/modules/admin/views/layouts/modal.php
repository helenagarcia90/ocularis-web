<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"
		aria-hidden="true">&times;</button>
	<h4 class="modal-title"><?=isset($title) ? $title : $this->pageTitle?></h4>
</div>
<div class="modal-body">
        <?=$content;?>
</div>
<?php if(isset($buttons)):?>	     
<div class="modal-footer">
    <?=$buttons;?>
</div>
<?php endif;?>