<?php
    $nombre_institucion = $this->App_model->nombre_institucion($row->institucion_id, 1);
    $nombre_grupo = $this->App_model->nombre_grupo($row->grupo_id, 1);
    
    $class_menu = array(
        'cuestionarios' =>  'class="a3"',
        'flipbooks' =>  'class="a3"',
    );
    
    $class_menu[$this->uri->segment(2)] = 'class="a3 seleccionado"';


    $fecha_editado = $this->Pcrn->fecha_formato($row_uc->editado, 'Y-M-d');
    $tiempo_hace = $this->Pcrn->tiempo_hace($row_uc->editado);
    
    $clases_rango = array(
        0 => '',
        1 => 'rango_bajo',
        2 => 'rango_medio_bajo',
        3 => 'rango_medio_alto',
        4 => 'rango_alto'
    );
    
    $texto_rango = array(
        0 => 'NA',
        1 => 'BAJO',
        2 => 'MEDIO BAJO',
        3 => 'MEDIO BAJO',
        4 => 'ALTO',
    );
?>

<p>
    <?= anchor("usuarios/cuestionarios/{$row->id}/1", '<i class="fa fa-arrow-left"></i> Respondidos', 'class="btn btn-default" title=""') ?>
    
    <?php if ( $this->session->userdata('rol_id') <= 5 ) : ?>                
        <?= anchor("cuestionarios/grupos/{$row_cuestionario->id}/{$row->institucion_id}/{$row->grupo_id}", '<i class="fa fa-users"></i> Estudiantes Grupo', 'class="btn btn-default" title=""') ?>
    <?php endif ?>
</p>

<div class="panel panel-default">
    <div class="panel-heading">
        Cuestionario: <?= $row_cuestionario->nombre_cuestionario ?>
    </div>
    <div class="panel-body">
        <p>
            Fecha de respuesta: <span class="resaltar"><?= $fecha_editado ?></span> |
            Hace: <span class="resaltar"><?= $tiempo_hace ?></span> |
            <?php if ( $this->session->userdata('rol_id') <= 2 ) { ?>

                Puntaje: <span class="resaltar"><?= $res_usuario['porcentaje'] ?>%</span> | 
                Rango: <span class="resaltar"><?= $texto_rango[$rango_usuario] ?></span>

            <?php } ?>
        </p>
    </div>
</div>

<?php $this->load->view($vista_menu); ?>
<?php $this->load->view($vista_c); ?>