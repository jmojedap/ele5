$('#submit_recurso').click(function(e) {
    
    cargar_registro_recurso();
    
    //alert('Elementos: ' + registro_recurso.length);
    
    $.ajax({        
        type: 'POST',
        url: '<?= base_url() ?>flipbooks/guardar_recurso/<?php echo $flipbook_id ?>',
        data: {registro : registro_recurso},
        success: function(data) {
            $("#recursos").load("<?php echo base_url() ?>flipbooks/recursos/<?php echo $flipbook_id ?>");
        }
    }); 

});