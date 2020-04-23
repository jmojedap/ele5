<div class="main_container container-fluid">   
    <h1>
        <span id="head_title"><?php echo $head_title ?></span>
        <?php if ( ! is_null($head_subtitle) ) { ?>
            <span id="head_subtitle"><?php echo $head_subtitle ?></span>
        <?php } ?>
    </h1>

    <?php if ( ! is_null($view_description) ) { ?>
        <div id="view_description">
            <?php $this->load->view($view_description) ?>
        </div>
    <?php } ?>

    <?php if ( ! is_null($nav_2) ) { ?>
        <div id="nav_2">
            <?php $this->load->view($nav_2) ?>
        </div>
    <?php } ?>

    <?php if ( ! is_null($nav_3) ) { ?>
        <div id="nav_3">
            <?php $this->load->view($nav_3) ?>
        </div>
    <?php } ?>

    <?php $this->load->view($view_a) ?>

    <?php if ( ! is_null($view_b) ) { ?>
        <div id="view_b">
            <?php $this->load->view($view_b) ?>
        </div>
    <?php } ?>
</div>
<footer class="main_footer text-right text-muted">&copy; 2019 - En Línea Editores</footer>