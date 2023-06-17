<?php

    $busqueda_str = "q={$busqueda['q_uri']}";

    $class_menu['estudiantes'] = '';
    $class_menu['usuarios'] = '';
    $class_menu['instituciones'] = '';
    $class_menu['programas'] = '';
    $class_menu['flipbooks'] = '';
    $class_menu['temas'] = '';
    $class_menu['cuestionarios'] = '';
    $class_menu['recursos'] = '';
    $class_menu['preguntas'] = '';
    

    $seccion = $this->uri->segment(2);
    
    $class_menu[$seccion] = 'class="current"';
?>
    
<div class="section group">
    <nav class="mini_nav">
        
        <?php if ( $this->session->userdata('rol_id') <= 2 ){ ?>
            <?= anchor("busquedas/instituciones/?{$busqueda_str}", '<i class="fa fa-bank"></i> Instituciones', $class_menu['instituciones']) ?>
        <?php } ?>
        
        
        <?php if ( $this->session->userdata('rol_id') <= 4 ){ ?>
            <?= anchor("busquedas/usuarios/?{$busqueda_str}", '<i class="fa fa-user"></i> Usuarios', $class_menu['usuarios']) ?>
        <?php } ?>
        
        <?php if (in_array($this->session->userdata('rol_id'), array(0, 1, 2, 3, 4, 5) ) ) : ?>                
            <?= anchor("flipbooks/explorar/?{$busqueda_str}", '<i class="fa fa-book"></i> Contenidos', $class_menu['flipbooks']) ?>
        <?php endif ?>
        
        <?php if ( $this->session->userdata('rol_id') <= 2  ):?>
            <?= anchor("programas/explorar/?{$busqueda_str}", '<i class="fa fa-sitemap"></i> Programas', $class_menu['programas']) ?>
            <?= anchor("temas/explore/?{$busqueda_str}", '<i class="fa fa-bars"></i> Temas', $class_menu['temas']) ?>
            <?= anchor("cuestionarios/explorar/?{$busqueda_str}", 'Cuestionarios', $class_menu['cuestionarios']) ?>
            <?= anchor("preguntas/explorar/?{$busqueda_str}", 'Preguntas', $class_menu['preguntas']) ?>
        <?php endif ?>
    </nav>
    
</div>

<?php $this->load->view($vista_b)?>



            
