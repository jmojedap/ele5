<?php
    //PlugIn, auto completar
    $this->load->view('head_includes/assets_autocomplete');
    $this->load->view('head_includes/html5sortable');  
?>

<?php

    $clase_modo = '';
    if ( $modo == 'editar' ) { $clase_modo = ''; }
        

    $att_q_elementos = array(
        'id'     => 'q_elementos',
        'name'   => 'q_elementos',
        'class'  => 'form-control',
        'placeholder'   => 'Agregar:'
    );
?>

<script>
//VARIABLES
//---------------------------------------------------------------------------------------------------

    var base_url = '<?= base_url() ?>';
    var tabla = '<?= $tabla ?>';
    var tabla_id = <?= $tabla_id ?>;
    var relacionado_id = '<?= $row->id ?>';
    var elemento_id = 0;
    var dato_id = 22;   //Elemento de lista
    var orden = <?= $elementos_lista->num_rows() ?>;
    var nombre_elemento = '';
    var meta_id = 0;
    var cant_elementos = <?= $elementos_lista->num_rows() ?>;
    var str_orden = '';
    
// Document Ready
//-----------------------------------------------------------------------------
    $(document).ready(function()
    {
        /**
         * Función genera autocompletar
         */
        $('#q_elementos').typeahead({
            ajax: {
                url: base_url + 'app/arr_elementos_ajax/' + tabla,
                method: 'post',
                triggerLength: 2
            },
            onSelect: agregar_elemento
        });
        
        $('.handles').sortable({
            handle: 'span',
            update: function( event, ui ) {
                str_orden = $(this).sortable('serialize');
                reordenar_lista();
            }
        });
        
        $('.quitar_elemento').click(function(){
            meta_id = $(this).data('meta_id');
            elemento_id = $(this).data('elemento_id');
            quitar_elemento();
        });
        
    });
    
// Functions
//-----------------------------------------------------------------------------
    
    //Ajax
    function agregar_elemento(item)
    {
        elemento_id = item.value;
        nombre_elemento = item.text;
    
        $.ajax({        
            type: 'POST',
            url: base_url + 'meta/guardar/',
            data: {
                tabla_id : tabla_id,
                elemento_id : elemento_id,
                relacionado_id : relacionado_id,
                dato_id : dato_id,
                orden : cant_elementos
            },
            success: function(){
                window.location = base_url + 'posts/lista/' + relacionado_id;
            }
        });
    }
    
    function quitar_elemento()
    {
        
       $.ajax({
            type: 'POST',
            url: base_url + 'app/eliminar_meta/',
            data: {
                meta_id : meta_id
            },
            success: function(){
                $('#elemento_' + elemento_id).hide();
                cant_elementos--;
                $('#cant_elementos').html(cant_elementos);
            }
        });
    }
    
    //Ajax
    function reordenar_lista()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + 'posts/reordenar_lista/' + relacionado_id,
            data: {
                str_orden : str_orden
            }
        });
    }
</script>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="sep2">
                    <?= form_input($att_q_elementos) ?>
                </div>

                <ul class="handles list" id="lista_elementos">
                    <?php foreach ($elementos_lista->result() as $row_elemento) : ?>
                        <li id="elemento_<?= $row_elemento->elemento_id ?>">
                            <span class="<?= $clase_modo ?>" class="">
                                ::
                            </span>

                            <?= $this->App_model->nombre($tabla, $row_elemento->elemento_id) ?>


                            <a class="pull-right a4 quitar_elemento <?= $clase_modo ?>" href="#" data-meta_id="<?= $row_elemento->id ?>" data-elemento_id="<?= $row_elemento->elemento_id ?>">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>

        </div>
    </div>
    
    <div class="col-md-4">
        <ul class="list-group">
            <li class="list-group-item">
                Lista de elementos de la tabla <span class="resaltar"><?= $tabla ?></span>
            </li>
            <li class="list-group-item">
                Número de elementos
                <span class="badge" id="cant_elementos"><?= $elementos_lista->num_rows() ?></span>
            </li>
            <li class="list-group-item">
                <?= $row->contenido ?>
            </li>
        </ul>
    </div>
</div>