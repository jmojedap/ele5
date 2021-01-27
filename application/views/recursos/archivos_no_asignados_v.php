<?php
    $carpeta_iconos = RUTA_IMG . 'flipbook/';
    
    $this->db->where('categoria_id', 20);
    $this->db->where('item_grupo', 1);
    $tipos_archivo = $this->db->get('item');
    $iconos = $this->Pcrn->query_to_array($tipos_archivo, 'slug', 'id');
    $carpetas = $this->Pcrn->query_to_array($tipos_archivo, 'slug', 'id');
    
    //Formulario
        $att_form = array(
            'class' => 'form1'
        );

        $att_q = array(
            'class' =>  'input1',
            'name' => 'q',
            'placeholder' => 'Buscar',
            'value' => $busqueda['q']
        );
        

        $att_submit = array(
            'class' =>  'button orange',
            'value' =>  'Buscar'
        );
?>

<script>
    //Variables
    tipo_archivo_id = <?= $tipo_archivo_id ?>;
    nombre_actual = '';
    nombre_nuevo = '';
    extension = '';
    key = 0;
    casilla_id = '#archivo_0';
</script>

<script>
    $(document).ready(function(){
        
        $('#formulario').hide();
        $('#mensaje_resultado').hide();
        
        $('.boton_editar_nombre').click(function(){
            key = $(this).data('key');
            casilla_id =  '#archivo_' + key;
            nombre_actual = $(casilla_id).data('nombre-actual');
            extension = $(casilla_id).data('extension');
            
            $('#formulario').show();
            $('#formulario').appendTo(casilla_id);
            $('#nombre_nuevo').val(nombre_actual);
            $('#nombre_nuevo').focus();
        });
        
        $('#cambiar_nombre').click(function(){
            nombre_nuevo = $('#nombre_nuevo').val();
            cambiar_nombre();
        });
        
    });
</script>

<script>
    function cambiar_nombre()
    {
        $.ajax({
            type: 'POST',
            url: '<?= base_url() ?>recursos/ajax_cambiar_nombre',
            data: {
                tipo_archivo_id : tipo_archivo_id,
                nombre_actual : nombre_actual,
                nombre_nuevo : nombre_nuevo,
                extension : extension
            },
            success: function(respuesta){
                $('#link_archivo_' + key).html(respuesta.basename_nuevo);
                //$(casilla_id).data('nombre-actual', nombre_nuevo);
                //$('#mensaje_resultado').html(respuesta.mensaje);
                actualizar_mensaje(respuesta);
                nombre_actual = nombre_nuevo;
            }
        });
    }
    
    function actualizar_mensaje(respuesta)
    {
        
        var clase_respuesta = 'alert_warning';
        if ( respuesta.asignado === 1 ) { clase_respuesta = 'alert_success'; }
        
        $('#mensaje_resultado').removeClass();
        $('#mensaje_resultado').show();
        $('#mensaje_resultado').addClass(clase_respuesta);
        $('#mensaje_resultado').html(respuesta.mensaje);
        
    }
</script>
    
<h4 class="" id="mensaje_resultado"></h4>
    
<div id="formulario">
    <div class="input-group">
        <input type="text" id="nombre_nuevo" class="form-control"/>
        <span class="input-group-btn" id="cambiar_nombre">
            <button class="btn btn-primary" type="button">
                <i class="fa fa-save"></i>
            </button>
        </span>
    </div>
    
</div>

<nav class="nav nav-pills mb-2">
    <?php foreach ($carpetas as $key => $carpeta) : ?>
        <?php
            $clase = '';
            if ( $key == $tipo_archivo_id ) { $clase = 'active'; }
        ?>
        <a href="<?= base_url("recursos/archivos_no_asignados/{$key}/") ?>" class="nav-item nav-link <?= $clase ?>">
            <?= $carpeta ?>
        </a>

    <?php endforeach ?>
</nav>

<div class="row">
    <div class="col col-md-8">
        <table class="table bg-white">
            <thead>
                <th width="50px">Tipo</th>
                <th>Nombre archivo</th>
                <th></th>
                <th width="50px"></th>
            </thead>
            <tbody>
                <?php foreach ($archivos as $key => $ruta_archivo) : ?>
                    <?php
                        $pathinfo = pathinfo($ruta_archivo);
                        $att_icono['src'] = "{$carpeta_iconos}{$iconos[$tipo_archivo_id]}.png";
                        $link = $carpeta_uploads . $pathinfo['basename'];

                        $texto_link = $pathinfo['filename'];
                        $img_link = img($att_icono);

                        if ( file_exists($ruta_archivo) ) {
                            $texto_link = anchor($link, $pathinfo['basename'], 'class="" title="" target="_blank" id="link_archivo_' . $key . '"');
                            $img_link = anchor($link, img($att_icono), 'class="" title="" target="_blank"');
                        }
                    ?>
                    <tr>
                        <td class="align_cen"><?= $img_link ?></td>
                        <td><?= $texto_link ?></td>
                        <td id="archivo_<?= $key ?>" data-nombre-actual="<?= $pathinfo['filename'] ?>" data-extension="<?= $pathinfo['extension'] ?>"></td>
                        <td>
                            <span class="btn btn-default btn-xs boton_editar_nombre" data-key="<?= $key ?>" title="Cambiar nombre y asignar">
                                <i class="fa fa-pencil"></i>
                            </span>
                        </td>
                        
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    
    <div class="col col-md-4">
        <div class="card ">
            <div class="card-header">
                Archivos no asignados
            </div>
            <div class="card-body">
                <ul>
                    <li>Los archivos que aparecen en esta sección están en la plataforma, pero no están asignados a ningún tema.</li>
                    <li>Para asignarlos puede cambiar el nombre del archivo. Haga clic en el botón <i class="fa fa-pencil"></i>.</li>
                    <li>Para que el archivo quede asignado automáticamente el nombre del archivo <span class="resaltar">debe iniciar</span> con el código del tema deseado.</li>
                    <li>Ejemplo: Si el archivo "un_archivo.mp3" se cambia por "m1-001a.mp3", se asignará al tema con código "m1-001"</li>
                    <li>Las extensiones del archivo (mp3, mp4, pdf, jpg, etc) no deben escribirse, estas se incluirán automáticamente.</li>
                </ul>
            </div>
        </div>
    </div>
</div>