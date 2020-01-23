<?php
    $folder = URL_ASSETS . 'grocery_crud/themes/bootstrap/'
?>

<link type="text/css" rel="stylesheet" href="<?php echo $folder ?>css/common.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $folder ?>css/list.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $folder ?>css/general.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $folder ?>css/plugins/animate.min.css" />
<link type="text/css" rel="stylesheet" href="<?php echo $folder ?>css/jquery_plugins/chosen/chosen.css" />
    
<script src="<?php echo $folder ?>js/jquery-1.11.1.min.js"></script>
<script src="<?php echo $folder ?>build/js/global-libs.min.js"></script>
<script src="<?php echo $folder ?>js/jquery-plugins/bootstrap-growl.min.js"></script>
<script src="<?php echo $folder ?>js/jquery-plugins/jquery.print-this.js"></script>
<script src="<?php echo $folder ?>js/datagrid/gcrud.datagrid.js"></script>
<script src="<?php echo $folder ?>js/datagrid/list.js"></script>
<script src="<?php echo $folder ?>js/jquery_plugins/jquery.chosen.min.js"></script>
<script src="<?php echo $folder ?>js/jquery_plugins/config/jquery.chosen.config.js"></script>
<script src="<?php echo $folder ?>js/form/edit.min.js"></script>
<script src="<?php echo $folder ?>js/jquery_plugins/jquery.noty.js"></script>
<script src="<?php echo $folder ?>js/jquery_plugins/config/jquery.noty.config.js"></script>

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