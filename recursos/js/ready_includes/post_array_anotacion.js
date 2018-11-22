$('#submit_anotacion').click(function(e) {
    cargar_registro_anotacion();
    
    $.ajax({        
        type: 'POST',
        url: '<?= base_url() ?>flipbooks/guardar_anotacion/<?php echo $flipbook_id ?>',
        data: {registro : registro_anotacion},
        success: function(data) {
            $("#anotaciones").load("<?php echo base_url() ?>flipbooks/anotaciones/<?php echo $flipbook_id ?>");
        }
    }); 

});