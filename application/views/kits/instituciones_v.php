<?php
    $num_institucion = 0;
    
    //Formulario
        $att_form = array(
            'class' => 'form-horizontal'
        );

        $att_q = array(
            'class' =>  'form-control',
            'name' => 'q',
            'placeholder' => 'Buscar',
            'value' => $busqueda['q']
        );


        $att_submit = array(
            'class' =>  'btn btn-primary',
            'value' =>  'Buscar'
        );
        
    //Colores etiqueta
        $colores = $this->App_model->arr_color_area();
        
    //Estados de asignación
        
        $estados[0] = 'Desactualizado';
        $estados[1] = 'Asignado';
        
        $clases_etiqueta[0] = 'danger';
        $clases_etiqueta[1] = 'success';
        
        
        //$nuevafecha = strtotime ('+1 year' , strtotime( date('Y-m-d'))) ;
        $nuevafecha = $this->Pcrn->suma_fecha(date('Y-m-d 23:59:59'), '+1 week');
?>

<div class="row">
    <div class="col col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Buscar para agregar
            </div>
            <div class="panel-body">
                <?= form_open("kits/instituciones/{$row->id}", $att_form) ?>
                    <div class="form-group">
                        <label for="q" class="col-sm-3 control-label">Institución</label>
                        <div class="col-sm-9">
                            <?= form_input($att_q) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <?= form_submit($att_submit) ?>
                        </div>
                    </div>
                <?= form_close('') ?>
                
            </div>
        </div>
        
        <?php if ( count($busqueda) > 0 ){ ?>
            <ul class="list-group">
                <?php foreach ($resultados->result() as $row_institucion) : ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col col-sm-2">
                                <span class="etiqueta primario w2"><?= $row_institucion->id ?></span>
                            </div>
                            <div class="col col-sm-8">
                                
                                <?= anchor("instituciones/grupos/{$row_institucion->id}", $row_institucion->nombre_institucion, 'target="_blank" title="Ir al institucion"') ?>
                            </div>
                            <div class="col col-sm-2">
                                <?= anchor("kits/agregar_institucion/{$row->id}/{$row_institucion->id}/?{$busqueda_str}", '<i class="fa fa-plus"></i>', 'class="btn btn-default btn-sm pull-right" title="Agregar el institucion al kit"') ?>
                            </div>
                        </div>

                    </li>
                <?php endforeach ?>
            </ul>
        <?php } ?>
    </div>
    
    <div class="col col-sm-8">
        <table class="table table-default bg-blanco">
            <thead>
                <th width="40px">No.</th>
                <th width="40px">ID</th>
                <th width="80px">Estado</th>
                <th width="40px">Actualizar</th>
                <th width="90px"><i class="fa fa-trash"></i> Actualizar</th>
                <th>Instituciones</th>
                <th>Actualizado</th>
                <th>Hace</th>
                <th width="40px"></th>
            </thead>
            <tbody>
                <?php foreach ($instituciones->result() as $row_institucion) : ?>
                    <?php
                        $num_institucion += 1;
                        $estado = 1;
                        if ( $row->editado > $row_institucion->editado ) { $estado = 0; }
                    ?>
                    <tr>
                        <td><?= $num_institucion ?></td>
                        <td class="warning"><?= $row_institucion->id ?></td>
                        <td class="<?= $clases_etiqueta[$estado] ?>"><?= $estados[$estado] ?></td>
                        <td><?= anchor("kits/asignar/{$row->id}/{$row_institucion->asignacion_id}/?{$busqueda_str}", '<i class="fa fa-refresh"></i>', 'class="btn btn-default" title="Actualizar la asignación del kit"') ?></td>
                        <td><?= $this->Pcrn->anchor_confirm("kits/asignar/{$row->id}/{$row_institucion->asignacion_id}/1/?{$busqueda_str}", '<i class="fa fa-refresh"></i>', 'class="btn btn-warning" title="Actualizar la asignación del kit eliminando las asignaciones de los elementos que ya no están en el kit"',  'Se eliminarán las asignaciones a los usuarios, de los contenidos y cuestionarios que ya no están en el kit. ¿Desea continuar?') ?></td>
                        <td><?= anchor("instituciones/flipbooks/{$row_institucion->id}", $row_institucion->nombre_institucion, 'target="_blank"') ?></td>
                        <td><?= $this->Pcrn->fecha_formato($row_institucion->editado) ?></td>
                        <td><?= $this->Pcrn->tiempo_hace($row_institucion->editado) ?></td>
                        <td><?= $this->Pcrn->anchor_confirm("kits/quitar_institucion/{$row->id}/{$row_institucion->asignacion_id}/?{$busqueda_str}", '<i class="fa fa-times"></i>', 'class="a4" title="Quitar a la institución del kit, se eliminarán las asignacione realizadas desde este kit"', 'Se eliminarán las asignaciones hechas a los usuarios de la Institución a partir de los elementos de este kit. ¿Desea continuar?') ?></td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>