$('#submit_resaltado').click(function(e) {
    cargar_registro_resaltado();
    
    $.ajax({        
        type: 'POST',
        url: '<?= base_url() ?>flipbooks/guardar_resaltado/<?php echo $flipbook_id ?>',
        data: {registro : registro_resaltado},
        success: function(data) {
            $("#lista_asignaciones").load("<?php echo base_url() ?>flipbooks/resaltados/<?php echo $flipbook_id ?>");
        }
    });

});