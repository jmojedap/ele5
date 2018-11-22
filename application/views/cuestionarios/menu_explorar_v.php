<?php
    $seccion = $this->uri->segment(3);
    $clases[$seccion] = 'active';
    
    if ( $this->uri->segment(2) == 'asignar_masivo_e' ) { $clases['asignar_masivo'] = 'active'; }
    if ( $this->uri->segment(2) == 'responder_masivo' ) { $clases['responder_masivo'] = 'active'; }
    if ( $this->uri->segment(2) == 'responder_masivo_e' ) { $clases['responder_masivo'] = 'active'; }
?>

<ul class="nav nav-tabs sep2">
    <li role="presentation" class="<?= $clases['plataforma'] ?>">
        <?= anchor("cuestionarios/explorar/plataforma", 'Plataforma', 'title="Cuestionarios de la Plataforma Enlace"') ?>
    </li>
    <li role="presentation" class="<?= $clases['mis_cuestionarios'] ?>">
        <?= anchor("cuestionarios/explorar/mis_cuestionarios", 'Mis cuestionarios', 'title="Mis cuestionarios"') ?>
    </li>
    <li role="presentation" class="<?= $clases['todos'] ?>">
        <?= anchor("cuestionarios/explorar/todos", 'Todos', 'title="Explorar cuestionarios"') ?>
    </li>
    <li role="presentation" class="<?= $clases['add'] ?>">
        <?= anchor("cuestionarios/nuevo/add", '<i class="fa fa-plus"></i> Nuevo', 'title="Nuevo cuestionario"') ?>
    </li>

    <?php if ( in_array($this->session->userdata('rol_id'), array(0,1,2)) ){ ?>
        <li role="presentation" class="<?= $clases['asignar_masivo'] ?>">
            <?= anchor("cuestionarios/asignar_masivo/asignar_masivo", '<i class="fa fa-file-excel-o"></i> Asignar', 'title="Asignar cuestionarios mediante archivo Excel"') ?>
        </li>
        <li role="presentation" class="<?= $clases['responder_masivo'] ?>">
            <?= anchor("cuestionarios/responder_masivo/", '<i class="fa fa-file-excel-o"></i> Cargar respuestas', 'title="Cargar respuestas con archivo MS-Excel"') ?>
        </li>
    <?php } ?>
</ul>    

