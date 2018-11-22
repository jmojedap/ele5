<?php $this->load->view('assets/grocery_crud'); ?>

<?php
    $clases[$tipo] = 'active';
?>

<script>
//Variables
//---------------------------------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var nombre = '';
    var apellidos = '';

//Document Ready
//---------------------------------------------------------------------------------------------------

    $(document).ready(function(){
        $('#field-apellidos').change(function(){
            nombre = $('#field-nombre').val();
            apellidos = $('#field-apellidos').val();
            
            if ( $('#field-username').val() === '' ) {
                generar_username();
            }
            
        });
        
        $('#generar_username').click(function(){
            
            nombre = $('#field-nombre').val();
            apellidos = $('#field-apellidos').val();
            
            var cant_condiciones = 0;
            
            if ( nombre.length > 0 ) { cant_condiciones +=  1;}
            if ( apellidos.length > 0 ) { cant_condiciones +=  1;}
            
            if ( cant_condiciones === 2 ) {
                generar_username();
            } else {
                alert('Escriba los nombres y apellidos');
            }
            
            
        });
    });

//Funciones
//---------------------------------------------------------------------------------------------------

    //Ajax
    function generar_username(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'usuarios/username',
            data: {
                nombre : nombre,
                apellidos : apellidos
            },
            success: function(respuesta){
               $('#field-username').val(respuesta);
            }
        });
    }
    
    function cambiar_username(username)
    {
        if ( $('#field-username').val() === '' ) 
        {
            $('#field-username').val(username);
        }
    }
</script>

<?php $this->load->view('usuarios/explorar_menu_v') ?>

<div class="sep2">
    <ul class="nav nav-pills">
        <li role="" class="<?= $clases['estudiante'] ?>">
            <?= anchor("usuarios/nuevo/estudiante/0/add", 'Estudiante') ?>
        </li>

        <li role="" class="<?= $clases['institucional'] ?>">
            <?= anchor("usuarios/nuevo/institucional/0/add", 'Institucional') ?>
        </li>

        <li role="" class="<?= $clases['interno'] ?>">
            <?= anchor("usuarios/nuevo/interno/0/add", 'Interno') ?>
        </li>
    </ul>      
</div>

<div class="sep3">
    <span class="btn btn-default" id="generar_username">Generar username</span>
</div>

<?php echo $output; ?>