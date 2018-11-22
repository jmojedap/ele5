<?php
    $seccion = $this->uri->segment(2);
    $clases[$seccion] = 'active';
    
    if ( $seccion == 'report_usuarios_01' ) { $clases['reportes'] = 'active'; }
    if ( $seccion == 'report_instituciones_01' ) { $clases['reportes'] = 'active'; }
    if ( $seccion == 'reporte_temas_02' ) { $clases['reportes'] = 'active'; }
    if ( $seccion == 'reporte_programas_01' ) { $clases['reportes'] = 'active'; }
?>

<ul class="nav nav-tabs">
  <li role="presentation" class="<?= $clases['tablas'] ?>">
      <?= anchor("develop/tablas/usuario", '<i class="fa fa-database"></i> Base de datos') ?>
  </li>
  
  <li role="presentation" class="<?= $clases['reportes'] ?>">
      <?= anchor("datos/report_usuarios_01", '<i class="fa fa-"></i> Reportes') ?>
  </li>
</ul>