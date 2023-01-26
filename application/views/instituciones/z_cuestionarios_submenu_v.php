<?php
    $clases = array(
        'cuestionarios' =>    'btn btn-default',
        'resumen01'   =>    'btn btn-default',
        'cuestionarios_resumen01'   =>    'btn btn-default',
        'cuestionarios_resumen02'   =>    'btn btn-default',
        'cuestionarios_resumen03'   =>    'btn btn-default',
        'resultados_lista'   =>    'btn btn-default',
        'estadisticas'   =>    'btn btn-default',
        'actualizar_acumulador'   =>    'btn btn-default',
    );
    
    $seccion = $this->uri->segment(2);
    
    $clases[$seccion] = 'btn btn-primary';
    
    if ( $seccion == 'resultados_area' ) { $clases['estadisticas'] = 'btn btn-primary'; }
    if ( $seccion == 'resultados_grupo' ) { $clases['estadisticas'] = 'btn btn-primary'; }
    if ( $seccion == 'resultados_competencia' ) { $clases['estadisticas'] = 'btn btn-primary'; }
    if ( $seccion == 'resultados_componente' ) { $clases['estadisticas'] = 'btn btn-primary'; }
?>

<div class="seccion group">
    <p>
        <?= anchor("instituciones/cuestionarios/$row->id", 'Listado', 'class="' . $clases['cuestionarios'] . '"') ?>
        <?= anchor("instituciones/cuestionarios_resumen03/$row->id/50/1", '<i class="fa fa-bar-chart-o"></i> Competencias', 'class="' . $clases['cuestionarios_resumen03'] . '"') ?>
        <?= anchor("instituciones/cuestionarios_resumen01/$row->id", '<i class="fa fa-bar-chart-o"></i> Competencias por cuestionario', 'class="' . $clases['cuestionarios_resumen01'] . '"') ?>
        <?php //anchor("instituciones/resultados_lista/{$row->id}", 'Detalle resultados', 'class="' . $clases['resultados_lista'] . '"') ?>
        <?= anchor("instituciones/resultados_grupo/{$row->id}", '<i class="fa fa-bar-chart-o"></i> Estadísticas', 'class="' . $clases['estadisticas'] . '"') ?>
    </p>
</div>