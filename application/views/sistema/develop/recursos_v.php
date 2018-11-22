<?php $this->load->view('sistema/develop/menu_v'); ?>

<?php
    
    $clase_menu[$this->uri->segment(2)] = 'a3 actual';
    
    $nombre_tabla = $this->uri->segment(2);
?>

<div class="section group">
    <div class="col col_box span_1_of_4">
        <div class="info_container_body">
            <?= anchor('develop/recursos_archivos', 'archivos', 'class="a3 ' . $clase_menu['recursos_archivos'] . '"') ?>
            <?= anchor('develop/recursos_links', 'links', 'class="a3 ' . $clase_menu['recursos_links'] . '"') ?>
            <?= anchor('develop/recursos_quices', 'quices', 'class="a3 ' . $clase_menu['recursos_quices'] . '"') ?>
            <?= anchor('develop/recursos_preguntas', 'preguntas', 'class="a3 ' . $clase_menu['recursos_preguntas'] . '"') ?>
        </div>
    </div>
    
    <div class="col col_box span_3_of_4">
        <?= $output; ?>
    </div>
</div>