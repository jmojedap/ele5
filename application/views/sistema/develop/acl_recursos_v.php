<?php $this->load->view('assets/grocery_crud'); ?>

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

<script>
    $(document).ready(function(){
        $('#field-funcion').change(function(){
            completar_campos();
        });
        
        $('#field-controlador').change(function(){
            completar_campos();
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    function completar_campos()
    {
        var field_recurso = $('#field-controlador').val() + '/' + $('#field-funcion').val();
        var field_titulo = $('#field-controlador').val() + ' ' + $('#field-funcion').val();
        $('#field-recurso').val(field_recurso);
        $('#field-titulo_recurso').val(field_titulo);
    }
</script>

<?php echo $output; ?>