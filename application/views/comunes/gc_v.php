<?php $this->load->view('assets/grocery_crud'); ?>

<?php if ( ! IS_NULL($vista_menu) ){ ?>
    <?php $this->load->view($vista_menu); ?>
<?php } ?>

<script>
    $(document).ready(function(){
        //Ajustes para tema bootstrap de grocery crud
            $('textarea').addClass('form-control');
        
        //Ajuste chosen downdrop
            $('.chzn-container').css('width', '300px');
            $('.chzn-drop').css('width', '300px');
            $('.chzn-drop').css('width', '300px');
            $('.chzn-search input').css('width', '280px');
        
    });
</script>

<div class="container">
    <?php echo $output; ?>
</div>

