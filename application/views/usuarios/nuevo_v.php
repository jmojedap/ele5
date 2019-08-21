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

<ul class="nav nav-pills">
    <li role="" class="nav-item">
        <a href="<?php echo base_url("usuarios/nuevo/estudiante/0/add") ?>" class="nav-link <?= $clases['estudiante'] ?>">
            Estudiante
        </a>
    </li>
    <li role="" class="nav-item">
        <a href="<?php echo base_url("usuarios/nuevo/institucional/0/add") ?>" class="nav-link <?= $clases['institucional'] ?>">
            Institucional
        </a>
    </li>
    <li role="" class="nav-item">
        <a href="<?php echo base_url("usuarios/nuevo/interno/0/add") ?>" class="nav-link <?= $clases['interno'] ?>">
            Interno
        </a>
    </li>
</ul>  

<div class="my-2">
    <span class="btn btn-secondary" id="generar_username">Generar username</span>
</div>

<?php echo $output; ?>