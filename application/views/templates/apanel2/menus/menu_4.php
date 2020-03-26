<?php
    
    //Instituciones
    $opciones_menus['instituciones/grupos'] = array('institucional', '', '');
    $opciones_menus['instituciones/usuarios'] = array('institucional', '', '');
    $opciones_menus['instituciones/flipbooks'] = array('institucional', '', '');
    $opciones_menus['instituciones/cuestionarios'] = array('institucional', '', '');
    $opciones_menus['instituciones/cuestionarios_resumen01'] = array('institucional', '', '');
    $opciones_menus['instituciones/cuestionarios_resumen03'] = array('institucional', '', '');
    $opciones_menus['instituciones/resultados_grupo'] = array('institucional', '', '');
    $opciones_menus['instituciones/resultados_area'] = array('institucional', '', '');
    $opciones_menus['instituciones/resultados_competencia'] = array('institucional', '', '');
    $opciones_menus['instituciones/resultados_componente'] = array('institucional', '', '');
    $opciones_menus['instituciones/nuevo_grupo'] = array('institucional', '', '');
    $opciones_menus['instituciones/cargar_grupos'] = array('institucional', '', '');
    $opciones_menus['instituciones/cargar_estudiantes'] = array('institucional', '', '');
    $opciones_menus['instituciones/asignar_profesores'] = array('institucional', '', '');
    $opciones_menus['instituciones/flipbooks'] = array('institucional', '', '');

    //Recursos académicos
    $opciones_menus['programas/explorar'] = array('recursos', 'programas', 'programas');
    $opciones_menus['programas/nuevo'] = array('recursos', 'programas', 'programas');
    $opciones_menus['programas/temas'] = array('recursos', 'programas', 'programas');
    $opciones_menus['programas/editar'] = array('recursos', 'programas', 'programas');
    $opciones_menus['programas/editar_temas'] = array('recursos', 'programas', 'programas');

    //Biblioteca
    $opciones_menus['grupos/estudiantes'] = array('institucional', '', '');

    //Grupos
    $opciones_menus['grupos/quices'] = array('institucional', '', '');
    $opciones_menus['grupos/cuestionarios_flipbooks'] = array('institucional', '', '');
    $opciones_menus['grupos/quitar_cuestionario'] = array('institucional', '', '');
    $opciones_menus['grupos/cuestionarios'] = array('institucional', '', '');
    $opciones_menus['grupos/cuestionarios_resumen02'] = array('institucional', '', '');
    $opciones_menus['grupos/profesores'] = array('institucional', '', '');
    $opciones_menus['grupos/quices'] = array('institucional', '', '');

    //Usuarios
    $opciones_menus['usuarios/actividad'] = array('institucional', '', '');
    $opciones_menus['usuarios/anotaciones'] = array('institucional', '', '');
    $opciones_menus['usuarios/quices'] = array('institucional', '', '');
    $opciones_menus['usuarios/cuestionarios'] = array('institucional', '', '');
    $opciones_menus['usuarios/cuestionarios_resumen02'] = array('institucional', '', '');
    $opciones_menus['usuarios/grupos'] = array('institucional', '', '');
    $opciones_menus['usuarios/resultados'] = array('institucional', '', '');

    //Cuestionarios
    $opciones_menus['cuestionarios/explorar'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
    $opciones_menus['cuestionarios/nuevo'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
    $opciones_menus['cuestionarios/vista_previa'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
    $opciones_menus['cuestionarios/asignar'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
    $opciones_menus['cuestionarios/preguntas'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
    $opciones_menus['cuestionarios/grupos'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');
    $opciones_menus['cuestionarios/editar'] = array('cuestionarios', 'cuestionarios-cuestionarios', 'cuestionarios');

    $opciones_menus['preguntas/explorar'] = array('cuestionarios', 'cuestionarios-preguntas', 'preguntas');
    $opciones_menus['preguntas/detalle'] = array('cuestionarios', 'cuestionarios-preguntas', 'preguntas');
    $opciones_menus['preguntas/cuestionarios'] = array('cuestionarios', 'cuestionarios-preguntas', 'preguntas');
    $opciones_menus['preguntas/editar'] = array('cuestionarios', 'cuestionarios-preguntas', 'preguntas');

    //Mensajes
    $opciones_menus['mensajes/conversacion'] = array('mensajes', '', '');
    $opciones_menus['mensajes/explorar'] = array('mensajes', '', '');

    //Estadisticas
    $opciones_menus['estadisticas/login_usuarios'] = array('estadisticas', 'estadisticas', 'estadísticas');
    $opciones_menus['estadisticas/login_diario'] = array('estadisticas', 'estadisticas', 'estadísticas');
    $opciones_menus['estadisticas/login_nivel'] = array('estadisticas', 'estadisticas', 'estadísticas');
    $opciones_menus['estadisticas/flipbooks_nivel'] = array('estadisticas', 'estadisticas', 'estadísticas');
    $opciones_menus['estadisticas/flipbooks_area'] = array('estadisticas', 'estadisticas', 'estadísticas');
    $opciones_menus['estadisticas/quices_area'] = array('estadisticas', 'estadisticas', 'estadísticas');
    $opciones_menus['estadisticas/quices_nivel'] = array('estadisticas', 'estadisticas', 'estadísticas');

    //Contraseña
    $opciones_menus['usuarios/contrasena'] = array('mi_cuenta', '', '');
    
        //Clases para menú izquierda
    $direccion = $this->uri->segment(1) . '/' . $this->uri->segment(2);
    $current_menu = $opciones_menus[$direccion];

    $m_current['menu'] = $current_menu[0];
    $m_current['submenu'] = $current_menu[1];
    $m_current['submenu_show'] = $current_menu[2];
        
    $clase_m[$m_current['menu']] = 'current';    //Clase menú
    $clase_sm[$m_current['submenu']] = 'current';    //Clase submenú

?>


<aside class="main_nav_col">
    
    <?= $this->load->view('templates/apanel2/encabezado') ?>
    <ul class="main_nav">	

        <li>
            <a href="<?= base_url() . 'instituciones/grupos' ?>" class="<?= $clase_m['institucional'] ?>">
                <i class="fa fa-2x fa-bank"></i> <!-- icono -->
                <span>institución</span> <!-- texto -->
            </a>
        </li>
        
        <li class="has_submenu">
            <a href="#" class="<?= $clase_m['cuestionarios'] ?>">
                <i class="fa fa-2x fa-question"></i>
                <span>cuestionarios</span>
            </a>
            
            <?php if ( $m_current['menu'] == 'cuestionarios' ){ ?>
                <span class="gossip"><?= $m_current['submenu_show'] ?></span>
            <?php } ?>

            <ul class="sub_nav">
                <li>
                    <a href="<?= base_url('cuestionarios/explorar/?f1=1') ?>" class="<?= $clase_sm['cuestionarios-cuestionarios'] ?>">
                        <i class="fa fa-book"></i>
                        <span>cuestionarios</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('preguntas/explorar')?>" class="<?= $clase_sm['cuestionarios-preguntas'] ?>">
                        <i class="fa fa-question"></i>
                        <span>preguntas</span>
                    </a>
                </li>
            </ul>

        </li>
        
        <li title="Links para asingar y programar a grupos de estudiantes">
            <a href="<?php echo base_url('recursos/links') ?>" class="<?php echo $clase_m['recursos'] ?>"><i class="fa fa-2x fa-link"></i><span>links</span></a>
        </li>
        <li title="Estadísticas de uso y desempeño en la plataforma">
            <a href="<?php echo base_url('estadisticas/login_usuarios') ?>" class="<?php echo $clase_m['estadisticas'] ?>"><i class="fa fa-2x fa fa-bar-chart-o"></i><span>estadísticas</span></a>
        </li>
        
        <li>
            <a href="<?= base_url() . 'mensajes/conversacion/0'?>" class="<?= $clase_m['mensajes'] ?>">
                <?= $this->load->view('plantilla_apanel/menu_mensajes_v'); ?>
            </a>
        </li>
        
        <li>
            <a href="<?= base_url() . 'datos/ayudas/' ?>" class="<?= $clase_m['ayuda'] ?>" ><i class="fa fa-2x fa-question-circle"></i><span>ayuda</span></a>
        </li>
        
        
        

    </ul>
</aside>