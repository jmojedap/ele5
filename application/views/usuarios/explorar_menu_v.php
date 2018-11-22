<?php
    $seccion = $this->uri->segment(2);
    if ( $seccion == 'eliminar_por_username' ) { $clases['importar'] = 'active'; }
    if ( $seccion == 'eliminar_por_username_e' ) { $clases['importar'] = 'active'; }
    if ( $seccion == 'importar_estudiantes' ) { $clases['importar'] = 'active'; }
    if ( $seccion == 'importar_estudiantes_e' ) { $clases['importar'] = 'active'; }
    
    $clases[$seccion] = 'active';
?>


<ul class="nav nav-tabs">
  <li role="presentation" class="<?= $clases['explorar'] ?>">
      <?= anchor("usuarios/explorar", 'Explorar') ?>
  </li>
  
  <?php if ($this->session->userdata('rol_id') <= 2) : ?>                
    <li role="presentation" class="<?= $clases['nuevo'] ?>">
        <?= anchor("usuarios/nuevo/estudiante/0/add", '<i class="fa fa-plus"></i> Nuevo') ?>
    </li>
    <li role="presentation" class="<?php echo $clases['importar'] ?>">
        <?php echo anchor("usuarios/importar_estudiantes/", '<i class="fa fa-upload"></i> Importar') ?>
    </li>
  <?php endif ?>
</ul>