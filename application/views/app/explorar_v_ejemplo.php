<?php

    $seccion = $this->uri->segment(2);

    //Formulario
        $att_form = array(
            'class' => 'form-inline',
            'role' => 'form'
        );

        $att_texto_busqueda = array(
            'class' =>  'form-control',
            'name' => 'texto_busqueda',
            'placeholder' => 'Buscar',
            'value' => $busqueda['q']
        );


        $att_submit = array(
            'class' =>  'btn btn-primary hidden-xs',
            'value' =>  'Buscar'
        );
        
    //Tabla de resultados
        $att_check_todos = array(
            'name' => 'check_todos',
            'id'    => 'check_todos',
            'checked' => FALSE
        );
?>

<script>
    //Variables
        var base_url = '<?= base_url() ?>';
        var seleccionados = '';
        var registro_id = 0;
</script>

<script>
    $(document).ready(function(){
        
        $('.check_registro').change(function(){
            registro_id = '-' + $(this).data('id');
            
            if( $(this).is(':checked') ) {  
                seleccionados += registro_id;
            } else {  
                seleccionados = seleccionados.replace(registro_id, '');
            }
            
            //$('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#check_todos').change(function() {
            
            if($(this).is(":checked")) { 
                //Activado
                $('.check_registro').prop('checked', true);
                $('.check_registro').each( function(key, element) {
                    seleccionados += '-' + $(element).data('id');
                });
            } else {
                //Desactivado
                $('.check_registro').prop('checked', false);
                seleccionados = '';
            }
            
            //$('#seleccionados').html(seleccionados.substring(1));
        });
        
        $('#eliminar_seleccionados').click(function(){
            eliminar();
        });
    });
</script>

<script>
    //Ajax
    function eliminar(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'tickets/eliminar_seleccionados',
            data: {
                seleccionados : seleccionados.substring(1)
            },
            success: function(){
                window.location = base_url + 'tickets/explorar';
            }
        });
    }
</script>

<div class="bs-caja">
    
    <div class="row">
        <div class="col-md-6 div2">
            <?= form_open("tickets/explorar/{$filtro}", $att_form) ?>
            <?= form_input($att_texto_busqueda) ?>
            <?= form_submit($att_submit) ?>
            <?= form_close() ?>
        </div>
        
        <div class="col-md-3 div2">
            <div class="btn-toolbar" role="toolbar" aria-label="...">
                <div class="btn-group" role="group" aria-label="...">
                    <a class="btn btn-warning" title="Eliminar los elementos seleccionados" data-toggle="modal" data-target="#modal_eliminar">
                        <i class="fa fa-trash-o"></i>
                    </a>            
                </div>

                <div class="btn-group hidden-xs" role="group">
                    <?= anchor("tickets/exportar/?{$busqueda_str}", '<i class="fa fa-file-excel-o"></i> Exportar (' . $cant_resultados . ')', 'class="btn btn-success" title="Exportar resultados a archivo de MS Excel"') ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 div2">
            <div class="pull-right">
                <?= $this->pagination->create_links(); ?>
            </div>
        </div>
    </div>
    
</div>

<div class="bs-caja-no-padding">
    <table class="table table-responsive table-hover" cellspacing="0">
        <thead>
            <tr class="">
                <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                <th width="50px;" class="warning">ID</th>
                <th>Nombre ticket</th>
                <th class="hidden-xs hidden-sm">Descripción</th>
                <th width="35px"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados->result() as $row_resultado){ ?>
            <?php
                //Variables
                    $nombre_ticket = word_limiter($row_resultado->nombre_ticket, 5);
                    $link_ticket = anchor("tickets/detalle/edit/$row_resultado->id", $nombre_ticket);
                    $editable = $this->Ticket_model->editable($row_resultado->id);

                //Checkbox
                    $att_check = array(
                        'class' =>  'check_registro',
                        'data-id' => $row_resultado->id,
                        'checked' => FALSE
                    );

            ?>
                <tr>
                    <td>
                        <?= form_checkbox($att_check) ?>
                    </td>
                    <td class="warning"><span class="etiqueta primario w1"><?= $row_resultado->id ?></span></td>
                    <td><?= $link_ticket ?></td>
                    <td class="hidden-xs hidden-sm"><?= word_limiter($row_resultado->descripcion, 15) ?></td>
                    <td>
                        <?php if ( $editable ){ ?>
                            <?= anchor("tickets/editar/edit/{$row_resultado->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                        <?php } ?>
                    </td>
                </tr>

            <?php } //foreach ?>
        </tbody>
    </table>    
</div>

<?php $this->load->view('app/modal_eliminar'); ?>