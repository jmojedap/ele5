<script>
// Variables
//-----------------------------------------------------------------------------
    var product_id = '<?php echo $row->id ?>';
    var src_default = '<?php echo URL_IMG ?>app/nd.png';

// Document Ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){

        //Al submit formulario, prevenir evento por defecto y ejecutar función ajax
        $('#file_form').submit(function()
        {
            send_form();
            return false;
        });

        $('#btn_remove_image').click(function(){
            remove_image();
        });
    });

// Functions
//-----------------------------------------------------------------------------

    /* Función AJAX para envío de archivo JSON a plataforma */
    function send_form()
    {
        var form = $('#file_form')[0];
        var form_data = new FormData(form);

        $.ajax({        
            type: 'POST',
            enctype: 'multipart/form-data', //Para incluir archivos en POST
            processData: false,  // Important!
            contentType: false,
            cache: false,
            url: app_url + 'products/set_image/' + product_id,
            data: form_data,
            beforeSend: function(){
                $('#status_text').html('Enviando archivo');
            },
            success: function(response){
                if ( response.status == 1 )
                {
                    $('#product_image').attr('src', response.src);
                    $('#image_section').show();
                    $('#image_form').hide();
                    $('#file_form')[0].reset();
                } else{
                    $('#upload_response').html(response.html);
                }
            }
        });
    }

    //Ajax
    function remove_image()
    {
       $.ajax({
            type: 'POST',
            url: app_url + 'products/remove_image/' + product_id,
            success: function (response) {
                if ( response.status == 1 )
                {
                    $('#product_image').attr('src', src_default);
                    $('#image_section').hide();
                    $('#image_form').show();
                    toastr['info']('La imagen del producto fue eliminada');
                }
            }
        });
    }
</script>