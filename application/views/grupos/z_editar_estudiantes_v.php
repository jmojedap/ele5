<script>
    $(document).ready(function(){
        
        //alert('#temas_temasinput_box');
        $('#estudiantes_input_box .selected').css( "height", "+=500" );
        $('#estudiantes_input_box .available').css( "height", "+=500" );
    });
</script>

<?php $this->load->view('grupos/submenu_estudiantes_v') ?>

<div class="">
    <?php echo $output; ?>
</div>