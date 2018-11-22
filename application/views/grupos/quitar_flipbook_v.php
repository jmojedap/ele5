<?php $this->load->view('assets/chosen_jquery'); ?>
<?php $this->load->view('assets/icheck'); ?>

<?php

    $att_link_flipbooks = 'class="a2"';

    $submit = array(
        'value' =>  'Quitar',
        'class' => 'btn btn-warning w3'
    );
            
    //Tabla de resultados
        $att_check_todos = array(
            'name' => 'check_todos',
            'id'    => 'check_todos',
            'checked' => FALSE
        );
        
        $att_check = array(
            'class' =>  'check_registro',
            'value' =>  1,
            'checked' => FALSE
        );

?>


<script>
    $(document).ready(function(){
        $('#check_todos').on('ifChanged', function(){
            
            if($(this).is(":checked"))
            {
                //Activado
                $('.check_registro').iCheck('check');
            } else {
                //Desactivado
                $('.check_registro').iCheck('uncheck');
            }
            
            //$('#seleccionados').html(seleccionados.substring(1));
        });
    });
</script>

<?php $this->load->view('grupos/submenu_flipbooks_v') ?>

<?= form_open("grupos/eliminar_asignacion_f/{$row->id}") ?>
<?= form_hidden('flipbook_id', $flipbook_id); ?>

<div class="row">
    <div class="col col-md-3">
        
        <div class="panel panel-default">
            <div class="panel-body">
                
                <p>
                    Seleccione el Contenido que quiere quitar de este grupo
                </p>
                
                <ul class="nav nav-pills nav-stacked sep1">
                    <?php foreach ($flipbooks->result() as $row_flipbook): ?>
                        <?php
                            $link_flipbook = "grupos/quitar_flipbook/{$grupo_id}/{$row_flipbook->flipbook_id}";
                            $nombre_flipbook_row = $this->App_model->nombre_flipbook($row_flipbook->flipbook_id);

                            $clase = '';
                            if ( $flipbook_id == $row_flipbook->flipbook_id )
                            {
                                $clase = 'active'; 
                            }
                        ?>

                        <li role="presentation" class="<?= $clase ?>">
                            <?= anchor($link_flipbook, $nombre_flipbook_row) ?>
                        </li>


                    <?php endforeach ?>
                </ul>
                
                <div class="sep1 pull-right">
                    <?= form_submit($submit) ?>        
                </div>
                
            </div>
        </div>
        
        

        

        <?php if ( $this->session->flashdata('resultado') != NULL ):?>
            <?php $resultado = $this->session->flashdata('resultado') ?>
            <div class="sep1">
                <div class="alert alert-success">
                    <i class="fa fa-info-circle"></i>
                    Se eliminaron <?= $resultado['num_eliminados'] ?> asignaciones de contenido
                </div>
            </div>
        <?php endif ?>
    </div>
    <div class="col col-md-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <h4>Quitar contenido a los estudiantes</h4>
                <p class="p1">
                    Los datos de asignación al contenido <span class="resaltar"><?= $nombre_flipbook ?></span> de los estudiantes que se seleccionen en las casillas serán <span class="resaltar">ELIMINADOS</span>.
                    Las anotaciones para este contenido de los estudiantes seleccionados también serán <span class="resaltar">ELIMINADAS</span>. Sea cuidadoso(a) con este proceso.
                </p>
            </div>
        </div>
        
        <table class="table table-default bg-blanco" cellspacing="0">
            <thead>
                <tr>
                    <th width="10px;"><?= form_checkbox($att_check_todos) ?></th>
                    <th>Nombre estudiante</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($estudiantes->result() as $row_estudiante): ?>
                    <?php
                        //Check
                        $att_check['name'] = $row_estudiante->id;
                    ?>
                    <tr>
                        <td width="50px"><?= form_checkbox($att_check) ?></td>
                        <td><?= $this->App_model->nombre_usuario($row_estudiante->id, 3) ?></td>

                    </tr>
                <?php endforeach ?>


            </tbody>
        </table>
    </div>
</div>

<?= form_close() ?>