<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php

    $elemento_s = 'mensaje';  //Elemento en singular
    $elemento_p = 'mensajes'; //Elemento en plural
    $controlador = $this->uri->segment(1);

    //Formulario
        $att_form = array(
            'class' => 'form-inline',
            'role' => 'form'
        );

        $att_q = array(
            'class' =>  'form-control',
            'name' => 'q',
            'autofocus' => TRUE,
            'placeholder' => 'Buscar',
            'value' => $busqueda['q']
        );


        $att_submit = array(
            'class' =>  'btn btn-primary',
            'value' =>  'Buscar'
        );
        
        //Opciones de dropdowns
        //$opciones_item = $this->Item_model->opciones('categoria_id = 2', 'Seleccione el item');
        
    //Tabla de resultados
        $att_check_todos = array(
            'name' => 'check_todos',
            'id'    => 'check_todos',
            'checked' => FALSE
        );
        
        $att_check = array(
            'class' =>  'check_registro',
            'checked' => FALSE
        );
        
        $seleccionados_todos = '';
        foreach ( $resultados->result() as $row_resultado ) {
            $seleccionados_todos .= '-' . $row_resultado->id;
        }
        
    //Clases columnas
        $clases_col['id'] = '';
        $clases_col['botones'] = 'hidden-xs hidden-sm';
        
        if ( $this->session->userdata('rol_id') > 1 ) {
            $clases_col['id'] = 'hidden';
            $clases_col['tipo'] = 'hidden';
        }
        
?>

<script>    
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var busqueda_str = '<?= $busqueda_str ?>';
    var seleccionados = '';
    var seleccionados_todos = '<?= $seleccionados_todos ?>';
    var registro_id = 0;
        
// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        
        $('.check_registro').on('ifChanged', function(){
            registro_id = '-' + $(this).data('id');
            if( $(this).is(':checked') ) {  
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
            
            //$('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#check_todos').on('ifChanged', function(){
            
            if($(this).is(":checked")) { 
                //Activado
                $('.check_registro').iCheck('check');
                seleccionados = seleccionados_todos;
            } else {
                //Desactivado
                $('.check_registro').iCheck('uncheck');
                seleccionados = '';
            }
            
            //$('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#eliminar_seleccionados').click(function(){
            eliminar();
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'mensajes/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'mensajes/explorar/?' + busqueda_str;
            }
        });
    }
</script>

<?php $this->load->view($vista_menu) ?>

<div class="row">
    <div class="col-md-6 sep1">
        <?= form_open("busquedas/explorar_redirect/{$controlador}", $att_form) ?>
        <?= form_input($att_q) ?>
        <?= form_submit($att_submit) ?>
        <?= form_close() ?>
    </div>

    <div class="col-md-3 col-xs-6 sep1">
        <div class="btn-toolbar" role="toolbar" aria-label="...">
            <div class="btn-group" role="group" aria-label="...">
                <a class="btn btn-warning" title="Eliminar los <?= $elemento_s ?> seleccionados" data-toggle="modal" data-target="#modal_eliminar">
                    <i class="fa fa-trash-o"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-xs-6 sep1">
        <div class="pull-right">
            <?= $this->pagination->create_links(); ?>
        </div>
    </div>
</div>

<div class="bs-caja-no-padding">
    <table class="table table-hover" cellspacing="0">
        <thead>
            <tr class="">
                <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                <th width="60px;" class="<?= $clases_col['id'] ?>">ID</th>

                <th class="<?= $clases_col['mensaje'] ?>">Conversación</th>
                <th class="<?= $clases_col['creado_por'] ?>">Iniciada por</th>
                <th class="<?= $clases_col['tipo'] ?>">Tipo</th>
                <th class="<?= $clases_col['editado'] ?>">Editado</th>
                <th class="<?= $clases_col['creado'] ?>">Creado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
                <?php
                    //Variables
                        $nombre_elemento = $this->Pcrn->si_strlen($row_resultado->asunto, '[ SIN ASUNTO ]');
                        $link_elemento = anchor("mensajes/conversacion/{$row_resultado->id}", $nombre_elemento);
                        if ( $this->session->userdata('rol_id') <= 1 ) {
                            $link_elemento = anchor("mensajes/mensajes/{$row_resultado->id}", $nombre_elemento);
                        }
                        

                    //Checkbox
                        $att_check['data-id'] = $row_resultado->id;

                ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>

                    <td class="warning text-right <?= $clases_col['id'] ?>"><?= $row_resultado->id ?></td>

                    <td>
                        <?= $link_elemento ?>
                    </td>
                    
                    <td class="<?= $clases_col['creado_por'] ?>">
                        <?= $this->App_model->nombre_usuario($row_resultado->usuario_id, 2); ?>
                    </td>
                    
                    <td class="<?= $clases_col['tipo'] ?>">
                        <?= $this->Item_model->nombre(61, $row_resultado->tipo_id); ?>
                    </td>
                    
                    <td class="<?= $clases_col['editado'] ?>">
                        <?= $this->Pcrn->tiempo_hace($row_resultado->editado); ?>
                    </td>
                    
                    
                    <td class="<?= $clases_col['creado'] ?>">
                        <?= $this->Pcrn->tiempo_hace($row_resultado->creado); ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>
</div>

<?php $this->load->view('app/modal_eliminar'); ?>