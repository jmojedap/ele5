<?php
    
//Clases para menú izquierda
    //Menú current
    $m_current = $this->App_model->menu_current($this->uri->segment(1), $this->uri->segment(2));
    
    $clase_m[$m_current['menu']] = 'current';    //Clase menú
    $clase_sm[$m_current['submenu']] = 'current';    //Clase submenú

?>


<aside class="main_nav_col">
    <ul class="main_nav">	

        <li class="has_submenu"> <!-- contenedor del item -->
            <a href="#" class="<?= $clase_m['institucional'] ?>"> <!-- link del item -->
                <i class="fa fa-2x fa-building-o"></i> <!-- icono -->
                <span>institucional</span> <!-- texto -->
            </a>
            <?php if ( $m_current['menu'] == 'institucional' ){ ?>
                <span class="gossip"><?= $m_current['submenu_show'] ?></span>
            <?php } ?>
            
            
            <ul class="sub_nav"><!-- SUBMENU -->
                <li><a href="<?= base_url() . 'instituciones/explorar' ?>" class="<?= $clase_sm['instituciones'] ?>"><i class="fa fa-building-o"></i><span>instituciones</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url() . 'grupos/explorar' ?>" class="<?= $clase_sm['grupos'] ?>"><i class="fa fa-users"></i><span>grupos</span></a></li> <!-- subitem -->
            </ul>
        </li>
        
        <li class="has_submenu"> <!-- contenedor item con submenu -->
            <a href="#" class="<?= $clase_m['usuarios'] ?>"><i class="fa fa-2x fa-users"></i><span>usuarios</span></a>
            <?php if ( $m_current['menu'] == 'usuarios' ){ ?>
                <span class="gossip"><?= $m_current['submenu_show'] ?></span>
            <?php } ?>

            <ul class="sub_nav"><!-- SUBMENU -->
                <li><a href="<?= base_url() . 'usuarios/estudiantes' ?>" class="<?= $clase_sm['estudiantes'] ?>"><i class="fa fa-user"></i><span>estudiantes</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url() . 'usuarios/institucionales' ?>" class="<?= $clase_sm['institucionales'] ?>" title="administradores, directivos, profesores"><i class="fa fa-user"></i><span>institucionales</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url() . 'datos/usuarios' ?>" class="<?= $clase_sm['internos'] ?>" title="usuarios en línea editores"><i class="fa fa-home"></i><span>internos</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url() . 'usuarios/explorar/lista/1' ?>" class="<?= $clase_sm['usuarios_explorar'] ?>"><i class="fa fa-check"></i><span>gestión de cuentas</span></a></li> <!-- subitem -->
            </ul>

        </li>

        <li class="has_submenu"> <!-- contenedor item con submenu -->
            <a href="#" class="<?= $clase_m['recursos'] ?>"><i class="fa fa-2x fa-book"></i><span>recursos académicos</span></a>
            <?php if ( $m_current['menu'] == 'recursos' ){ ?>
                <span class="gossip"><?= $m_current['submenu_show'] ?></span>
            <?php } ?>

            <ul class="sub_nav"><!-- SUBMENU -->
                <li><a href="<?= base_url() ?>programas/explorar" class="<?= $clase_sm['programas'] ?>"><i class="fa fa-sitemap"></i><span>programas</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url()?>temas/explorar" class="<?= $clase_sm['temas'] ?>"><i class="fa fa-bars"></i><span>temas</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url()?>datos/flipbooks" class="<?= $clase_sm['flipbooks'] ?>"><i class="fa fa-book"></i><span>flipbooks</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url()?>datos/recursos" class="<?= $clase_sm['links'] ?>"><i class="fa fa-desktop"></i><span>recursos y links</span></a></li> <!-- subitem -->
            </ul>
        </li>
        
        <li class="has_submenu"> <!-- contenedor item con submenu -->
            <a href="#" class="<?= $clase_m['cuestionarios'] ?>"><i class="fa fa-2x fa-question"></i><span>cuestionarios</span></a>
            
            <?php if ( $m_current['menu'] == 'cuestionarios' ){ ?>
                <span class="gossip"><?= $m_current['submenu_show'] ?></span>
            <?php } ?>

            <ul class="sub_nav"><!-- SUBMENU -->
                <li><a href="<?= base_url() ?>cuestionarios/explorar" class="<?= $clase_sm['cuestionarios'] ?>"><i class="fa fa-book"></i><span>cuestionarios</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url()?>datos/enunciados" class="<?= $clase_sm['enunciados'] ?>"><i class="fa fa-quote-left"></i><span>enunciados</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url()?>datos/preguntas" class="<?= $clase_sm['preguntas'] ?>"><i class="fa fa-question"></i><span>preguntas</span></a></li> <!-- subitem -->
            </ul>

        </li>
        
        <li class="has_submenu"> <!-- contenedor item con submenu -->
            <a href="#" class="<?= $clase_m['panel_control'] ?>"><i class="fa fa-2x fa-dashboard"></i><span>panel de control</span></a>
            <?php if ( $m_current['menu'] == 'panel_control' ){ ?>
                <span class="gossip"><?= $m_current['submenu_show'] ?></span>
            <?php } ?>

            <ul class="sub_nav"><!-- SUBMENU -->
                <li><a href="<?= base_url() ?>datos/contenidos" class="<?= $clase_sm['parametros'] ?>"><i class="fa fa-gear"></i><span>parámetros</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url()?>bd_admin/procesos" class="<?= $clase_sm['procesos'] ?>"><i class="fa fa-tasks"></i><span>procesos</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url()?>estadisticas/login_diario" class="<?= $clase_sm['estadisticas'] ?>"><i class="fa fa-bar-chart-o"></i><span>estadísticas</span></a></li> <!-- subitem -->
            </ul>
        </li>
        
        <li class="has_submenu"> <!-- contenedor item con submenu -->
            <a href="#" class="<?= $clase_m['mi_cuenta'] ?>"><i class="fa fa-2x fa-user"></i><span>mi cuenta</span></a>
            <?php if ( $m_current['menu'] == 'mi_cuenta' ){ ?>
                <span class="gossip"><?= $m_current['submenu_show'] ?></span>
            <?php } ?>

            <ul class="sub_nav"><!-- SUBMENU -->
                <li><a href="<?= base_url() ?>mensajes/recibidos" class="<?= $clase_sm['mensajes'] ?>"><i class="fa fa-comments"></i><span>mensajes</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url() ?>usuarios/contrasena" class="<?= $clase_sm['contrasena'] ?>"><i class="fa fa-lock"></i><span>contraseña</span></a></li> <!-- subitem -->
                <li><a href="<?= base_url() ?>app/logout"><i class="fa fa-sign-out"></i><span>cerrar sesión</span></a></li> <!-- subitem -->
            </ul>
        </li>

    </ul>
</aside>