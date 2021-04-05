<?php
    $clase_menu = array(
        'links' => '',
        'cargar_links' => ''
    );
    
    $clase_menu[$this->uri->segment(2)] = 'current';
    
    if ( $this->uri->segment(2) == 'procesar_cargue' ) { $clase_menu['asignar'] = 'current'; }

?>
hola
<nav class="mini_nav">
    <?= anchor("recursos/links", '<i class="fa fa-globe"></i> Links', 'title="" class="'. $clase_menu['links'] .'"') ?>
    <?= anchor("recursos/cargar_links", '<i class="fa fa-table"></i> Cargar', 'title="Cargar listado de archivos para temas - MS Excel" class="'. $clase_menu['cargar_links'] .'"') ?>
    <?= $this->Pcrn->anchor_confirm("recursos/eliminar_links", 'Eliminar links', 'class="" title="Eliminar todos los links"', 'Se eliminaran todos los links externos de los temas ¿Desea continuar?') ?>
</nav>

<?php $this->load->view($vista_b) ?>