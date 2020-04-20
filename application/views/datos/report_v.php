<?php 
    $seccion = $this->uri->segment(2);

    $clases[$seccion] = 'active';
    
    if ( $seccion == 'report_usuarios_01' ) { $clases['report_usuarios_01'] = 'active'; }
    
    $subseccion = $this->uri->segment(3);
    $clases[$subseccion] = 'active';
    
?>

<?php $this->load->view('sistema/develop/database_menu_v') ?>


<div class="row">
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                Reportes de usuarios
            </div>
            <div class="panel-body">
                <h3>Reportes de usuarios</h3>
                <div class="list-group">
                    <?= anchor('datos/report_usuarios_01', 'Usuarios activación y pagos', 'class="list-group-item ' . $clases['report_usuarios_01'] . '" title=""') ?>
                </div>

                <h3>Reportes de Instituciones</h3>
                    <div class="list-group">
                        <?= anchor('datos/report_instituciones_01', 'Instituciones, acceso de usuarios', 'class="list-group-item ' . $clases['report_instituciones_01'] . '"') ?>
                    </div>
                    

                <h3>Otros reportes</h3>
                
                <div class="list-group">
                    <?= anchor('datos/reporte_general/reporte_instituciones_01', 'Listado de instituciones', 'class="list-group-item ' . $clases['reporte_instituciones_01'] . '"') ?>
                    <?= anchor('datos/reporte_general/reporte_temas_01', 'Listado de temas', 'class="list-group-item ' . $clases['reporte_temas_01'] . '"') ?>
                    <?= anchor('datos/reporte_programas_01', 'Programas - Contenidos', 'class="list-group-item ' . $clases['reporte_programas_01'] . '"') ?>
                    <?= anchor('datos/reporte_quices_01', 'Quices', 'class="list-group-item ' . $clases['reporte_quices_01'] . '"') ?>
                    <?= anchor('datos/reporte_links_01', 'Enlaces', 'class="list-group-item ' . $clases['reporte_links_01'] . '"') ?>
                    <?= anchor('datos/reporte_temas_02', 'Programas - Temas', 'class="list-group-item ' . $clases['reporte_temas_02'] . '"') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <?php $this->load->view($vista_b); ?>
    </div>
</div>