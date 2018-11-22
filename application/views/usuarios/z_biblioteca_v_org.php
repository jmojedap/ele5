<?php
    $seccion = $this->uri->segment(2);
    $clases[$seccion] = 'active';

    $url_resources = base_url() . RUTA_IMG . 'flipbook_resources/';
    
    //Atributos estante flipbooks
        $img_flipbook_general = $url_resources . 'flipbook_general.png';

        $att_flipbooks['ancho'] = 155 * $flipbooks->num_rows();
        $att_flipbooks['overflow_x'] = 'hidden';

        if ( $att_flipbooks['ancho'] > 700 ) { $att_flipbooks['overflow_x'] = 'scroll'; }
        
    //Atributos estante flipbooks
        $img_anotaciones_general = $url_resources . 'anotaciones_general.jpg';

        $att_anotaciones['ancho'] = 155 * $flipbooks->num_rows();
        $att_anotaciones['overflow_x'] = 'hidden';

        if ( $att_anotaciones['ancho'] > 700 ) { $att_anotaciones['overflow_x'] = 'scroll'; }
        
    //Atributos estante evaluaciones
        $img_cuestionario_general = $url_resources . 'cuestionario_general.jpg';

        $att_cuestionarios['ancho'] = 155 * $cuestionarios->num_rows();
        $att_cuestionarios['overflow_x'] = 'hidden';

        if ( $att_cuestionarios['ancho'] > 700 ) { $att_cuestionarios['overflow_x'] = 'scroll'; }
        
    //Link anotaciones
        $link_anotaciones = base_url() . 'usuarios/anotaciones/' . $this->session->userdata('usuario_id') . '/';
    
?>

<link rel="stylesheet" href="<?php echo URL_RECURSOS ?>plantillas/apanel2/biblioteca.css">

<?php $this->load->view('usuarios/biblioteca_menu_v'); ?>

<article style="padding-top: 40px; width: 786px; height: 1017px; background:url(<?php echo URL_IMG ?>biblio/bookshelf.png) no-repeat;" class="module width_full">
    <h1 class="estante">
        Contenidos
    </h1>

    <div class="flipbooks_wrapper" style="height: 154px; overflow-x: <?= $att_flipbooks['overflow_x'] ?>; overflow-y: hidden; width: 715px; margin: 0px auto;">
        <div class="flipbooks_strip" style="width: <?= $att_flipbooks['ancho'] ?>px;">
            <?php foreach ($flipbooks->result() as $row_flipbook): ?>
                <?php 
                    $imagen_flipbook = $url_resources . "flipbook-{$row_flipbook->area_id}-{$row_flipbook->nivel}-mini.jpg";
                ?>
                <div class="flipbook_container">
                    <a target="_blank" href="<?= base_url() . 'flipbooks/' . $funcion_flipbook . '/' . $row_flipbook->flipbook_id; ?>">
                        <img width="100" src="<?= $imagen_flipbook ?>" onError="this.src='<?= $img_flipbook_general ?>'">
                        <!--<span style="position: absolute; bottom: 0px; left: 0px; width: 100px; background: rgba(255, 255, 255, 0.75);"><? $row_flipbook->nombre_flipbook ?></span>-->
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <h1 class="estante">
        Anotaciones
    </h1>

    <div class="flipbooks_wrapper" style="height: 152px; overflow-x: <?= $att_flibooks['overflow_x'] ?>; overflow-y: hidden; width: 715px; margin: 0px auto;">

        <div class="flipbooks_strip" style="width: <?= $att_flipbooks['ancho'] ?>px;">
            <?php foreach ($flipbooks->result() as $row_flipbook): ?>
                <?php 
                    $imagen_anotaciones = $url_resources . "anotaciones-{$row_flipbook->area_id}-{$row_flipbook->nivel}-mini.jpg";
                ?>
                <div class="flipbook_container">
                    <a href="<?=  $link_anotaciones . $row_flipbook->flipbook_id; ?>">
                        <img width="100" src="<?= $imagen_anotaciones ?>" onError="this.src='<?= $img_anotaciones_general ?>'">
                    </a>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    
    <h1 class="estante">
        Mis evaluaciones
    </h1>

    <div class="flipbooks_wrapper" style="height: 154px; overflow-x: <?= $att_cuestionarios['overflow_x'] ?>; overflow-y: hidden; width: 715px; margin: 0px auto;">
        <div class="flipbooks_strip" style="width: <?= $att_cuestionarios['ancho'] ?>px;">
            <?php foreach ($cuestionarios->result() as $row_cuestionario): ?>
                <?php 
                    $imagen_cuestionario = $url_resources . "cuestionario-{$row_cuestionario->area_id}-{$row_cuestionario->nivel}-mini.jpg";
                ?>
                <div class="flipbook_container">
                    <a href="<?= base_url("cuestionarios/preliminar/{$row_cuestionario->uc_id}") ?>">
                        <img width="100" src="<?= $imagen_cuestionario ?>" onError="this.src='<?= $img_cuestionario_general ?>'">
                        <span style="position: absolute; bottom: 0px; left: 0px; width: 100px; background: rgba(255, 255, 255, 0.75);"><?= $row_cuestionario->nombre_cuestionario ?></span>
                    </a>
                </div>
            <?php endforeach ?>

        </div>
    </div>
    
    <h1 class="estante">
        Acompañamiento Pedagógico
    </h1>
    <div class="flipbooks_wrapper" style="height: 150px; overflow-x: <?= $att_cap['overflow_x'] ?>; overflow-y: hidden; width: 715px; margin: 0px auto;">

        <div class="flipbooks_strip" style="width: <?= $att_cap['ancho'] ?>px;">
            <div class="flipbook_container">
                <a href="<?= base_url("posts/ap_explorar/?f1=1") ?>" title="Estrategias pedagógicas">
                    <img width="100" src="<?php echo URL_IMG . 'flipbook_resources/estrategias-pedagogicas.jpg' ?>" onError="this.src='<?= $img_cap_general ?>'">
                </a>
            </div>
        </div>
    </div>
</article>