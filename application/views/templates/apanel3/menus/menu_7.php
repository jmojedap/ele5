<?php
    
//Clases para menú izquierda
    //Menú current
    $m_current = $this->App_model->menu_current($this->uri->segment(1), $this->uri->segment(2));
    
    $clase_m[$m_current['menu']] = 'current';    //Clase menú
    $clase_sm[$m_current['submenu']] = 'current';    //Clase submenú

?>

<aside class="main_nav_col">
    <?php $this->load->view('templates/apanel3/parts/header') ?>
    <ul class="main_nav">
        
        <li class="">
            <a href="<?= base_url() ?>cuestionarios/explorar/todos" class="<?= $clase_m['cuestionarios'] ?>"><i class="fa fa-2x fa-question"></i><span>cuestionarios</span></a>
        </li>
        
        <li class="">
            <a href="<?= base_url() . 'mensajes/conversacion/0'?>" class="<?= $clase_m['mensajes'] ?>">
                <?= $this->load->view('plantilla_apanel/menu_mensajes_v'); ?>
            </a>
        </li>
        
        <li class=""> 
            <a href="<?= base_url() ?>usuarios/contrasena" class="<?= $clase_m['mi_cuenta'] ?>"><i class="fa fa-2x fa-user"></i><span>mi cuenta</span></a>
        </li>
        
        <li class="">
            <a href="<?= base_url() . 'datos/ayudas/' ?>" class="<?= $clase_m['ayuda'] ?>" ><i class="fa fa-2x fa-question-circle"></i><span>ayuda</span></a>
        </li>
    </ul>
</aside>