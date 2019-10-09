<script>
    var base_url = '<?php echo base_url() ?>';
    var pregunta_id = '<?php echo $row->id ?>';

    $(document).ready(function(){
        $('#btn_create_version').click(function(){
            create_version();
        });
    });

    function create_version(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'preguntas/create_version/' + pregunta_id,
            success: function(response){
                window.location = base_url + 'preguntas/version/' + pregunta_id +'/editar';
            }
        });
    }
</script>

<div class="alert alert-info">
    <i class="fa fa-info-circle"></i>
    Esta pregunta no tiene creada una versión propuesta
</div>

<button class="btn btn-primary" id="btn_create_version">
    Crear versión propuesta
</button>