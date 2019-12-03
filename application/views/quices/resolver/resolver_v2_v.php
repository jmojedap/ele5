<script>
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';
    var resultado = 0;
    var quiz_id = '<?php echo $row->id; ?>';
    var usuario_id = '<?php echo $this->session->userdata('usuario_id'); ?>';

// FUNCIONES
//-----------------------------------------------------------------------------
    function guardar_resultado(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'quices/guardar_resultado',
            data: {
                usuario_id : usuario_id,
                quiz_id : quiz_id,
                resultado : resultado
            },
            success: function(response){
                //console.log(response.message);
            }
        });
    }
</script>

<?php $this->load->view($view_a) ?>