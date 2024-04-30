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
        1 => '[1] BAJO',
        2 => '[2] MEDIO BAJO',
        3 => '[3] MEDIO ALTO',
        4 => '[4] ALTO',
    );
?>

<p>
    <a href="<?php echo base_url("usuarios/cuestionarios/{$row->id}/") ?>" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Respondidos
    </a>
    
    <?php if ( $this->session->userdata('rol_id') <= 5 ) : ?>
        <a href="<?php echo base_url("cuestionarios/grupos/{$row_cuestionario->id}/{$row->institucion_id}/{$row->grupo_id}") ?>" class="btn btn-outline-secondary">
            <i class="fa fa-users"></i> Estudiantes Grupo
        </a>
    <?php endif ?>
</p>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            Cuestionario: <?= $row_cuestionario->nombre_cuestionario ?>
        </h5>
        <p>
            Fecha de respuesta: <span class="text-primary"><?= $fecha_editado ?></span> &middot;
            Hace: <span class="text-primary"><?= $tiempo_hace ?></span> &middot;
            <?php if ( $this->session->userdata('rol_id') <= 2 ) { ?>

                Puntaje: <span class="text-primary"><?= $res_usuario['porcentaje'] ?>%</span> &middot; 
                Rango: <span class="text-primary"><?= $texto_rango[$rango_usuario] ?></span>
            <?php } ?>
        </p>
    </div>
</div>

<?php $this->load->view($vista_menu); ?>
<?php $this->load->view($vista_c); ?>