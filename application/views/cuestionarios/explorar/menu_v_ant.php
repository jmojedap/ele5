<?php
    $seccion = $this->uri->segment(3);
    $clases[$seccion] = 'active';
    
    if ( $this->uri->segment(2) == 'asignar_masivo_e' ) { $clases['asignar_masivo'] = 'active'; }
    if ( $this->uri->segment(2) == 'responder_masivo' ) { $clases['responder_masivo'] = 'active'; }
    if ( $this->uri->segment(2) == 'responder_masivo_e' ) { $clases['responder_masivo'] = 'active'; }
    if ( $this->uri->segment(2) == 'asignaciones' ) { $clases['asignaciones'] = 'active'; }
?>

<ul class="nav nav-tabs sep1">
    <?php if ( $this->session->userdata('srol') == 'institucional' ) { ?>
        <li role="presentation" class="nav-item <?php echo $clases['plataforma'] ?>">
            <?php echo anchor("cuestionarios/explorar/plataforma", 'Plataforma', 'class="nav-link" title="Cuestionarios de la Plataforma Enlace"') ?>
        </li>
        <li role="presentation" class="<?php echo $clases['mis_cuestionarios'] ?>">
            <?php echo anchor("cuestionarios/explorar/mis_cuestionarios", 'Mis cuestionarios', 'class="nav-link" title="Mis cuestionarios"') ?>
        </li>
    <?php } ?>
    
    <li role="presentation" class="nav-item <?php echo $clases['todos'] ?>">
        <a href="<?php echo base_url('cuestionarios/explorar/todos') ?>" class="nav-link <?php echo $clases['todos'] ?>" title="Explorar cuestionarios">
            Todos
        </a>
    </li>
    
    <?php if ( $this->session->userdata('srol') == 'interno' ) { ?>
        <li role="presentation" class="nav-item">
            <a href="<?php echo base_url(cuestionarios/asignaciones) ?>" class="nav-link <?php echo $clases['asignaciones'] ?>" title="Explorar asignación de cuestionarios">
                <i class="fa fa-users"></i>            
                Asignaciones
            </a>
        </li>
    <?php } ?>
    
    <li role="presentation" class="nav-item">
        <a href="<?php echo base_url('cuestionarios/nuevo/add'); ?>" class="nav-link <?php echo $clases['add'] ?>" title="Nuevo cuestionario">
            <i class="fa fa-plus"></i> Nuevo
        </a>
    </li>

    <?php if ( in_array($this->session->userdata('rol_id'), array(0,1,2)) ){ ?>
        <li role="presentation" class="nav-item">
            <a href="<?php echo base_url("cuestionarios/asignar_masivo/asignar_masivo") ?>" class="nav-link <?php echo $clases['asignar_masivo'] ?>" title="Asignar cuestionarios mediante archivo Excel">
                <i class="fa fa-file-excel-o"></i> Asignar
            </a>
        </li>
        <li role="presentation" class="nav-item">
            <a href="<?php echo base_url('cuestionarios/responder_masivo/') ?>" class="nav-link <?php echo $clases['responder_masivo'] ?>" title="Cargar respuestas con archivo MS-Excel">
                <i class="fa fa-file-excel-o"></i> Cargar respuestas
            </a>
        </li>
    <?php } ?>
</ul>    

