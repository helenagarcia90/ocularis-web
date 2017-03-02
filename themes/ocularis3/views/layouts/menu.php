<nav class="navbar">

    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="<?=Yii::app()->theme->baseUrl."/"; ?>images/logo.png" /></a>
        </div>

        <div class="collapse navbar-collapse navbar-ex1-collapse">

            <?php $this->widget('webroot.themes.ocularis2.components.BootstrapMenu', array('id' => 'mainmenu',
                'anchor' => 'top-menu',
            )); ?>

        </div>

    </div>

</nav>