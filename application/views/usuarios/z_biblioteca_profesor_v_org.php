<?php
    $url_resources = URL_IMG . 'flipbook_resources/';
    
    //Atributos estante flipbooks
        $img_flipbook_general = $url_resources . 'flipbook_general.png';

        $att_flipbooks['ancho'] = 170 * $flipbooks->num_rows();
        $att_flipbooks['overflow_x'] = 'hidden';

        if ( $att_flipbooks['ancho'] > 700 ) { $att_flipbooks['overflow_x'] = 'scroll'; }
        
    //Atributos estante talleres
        $img_flipbook_general = $url_resources . 'flipbook_general.png';

        $att_talleres['ancho'] = 170 * $talleres->num_rows();
        $att_talleres['overflow_x'] = 'hidden';

        if ( $att_talleres['ancho'] > 700 ) { $att_flibooks['overflow_x'] = 'scroll'; }
        
    //Atributos estante evaluaciones
        $img_cuestionario_alt =  URL_IMG . 'app/pf_nd_1.png';

        $att_cuestionarios['ancho'] = 170 * $cuestionarios->num_rows();
        $att_cuestionarios['overflow_x'] = 'hidden';

        if ( $att_cuestionarios['ancho'] > 700 ) { $att_flibooks['overflow_x'] = 'scroll'; }
        
    //Anotaciones
        $img_anotaciones_general = $url_resources . 'anotaciones_general.jpg';
        $link_anotaciones = base_url() . 'usuarios/anotaciones/' . $this->session->userdata('usuario_id') . '/';
        
    //Contenidos Acompañamiento Pedagógico
        $img_cap_general = $url_resources . 'cap_general.jpg';
        
        //$att_cap['ancho'] = 170 * $contenidos_ap->num_rows();
        $att_cap['ancho'] = 170 * 4;
        $att_cap['overflow_x'] = 'hidden';

        if ( $att_cap['ancho'] > 700 ) { $att_cap['overflow_x'] = 'scroll'; }
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
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <h1 class="estante">
        Actividades resueltas
    </h1>

    <div class="talleres_wrapper" style="height: 150px; overflow-x: <?= $att_flibooks['overflow_x'] ?>; overflow-y: hidden; width: 715px; margin: 0px auto;">
        <div class="talleres_strip" style="width: <?= $att_talleres['ancho'] ?>px;">
            <?php foreach ($talleres->result() as $row_taller): ?>
                <?php 
                    $imagen_taller = $url_resources . "taller-{$row_taller->area_id}-{$row_taller->nivel}-mini.jpg";
                    //echo $imagen_taller;
                ?>
                <div class="flipbook_container">
                    <a target="_blank" href="<?= base_url() . 'flipbooks/' . $funcion_flipbook . '/' . $row_taller->flipbook_id; ?>">
                        <img width="100" src="<?= $imagen_taller ?>" onError="this.src='<?= $imagen_taller_general ?>'">
                        <!--<span style="position: absolute; bottom: 0px; left: 0px; width: 100px; background: rgba(255, 255, 255, 0.75);"><? $row_taller->nombre_flipbook ?></span>-->
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <h1 class="estante">
        Anotaciones
    </h1>

    <div class="flipbooks_wrapper" style="height: 150px; overflow-x: <?= $att_flibooks['overflow_x'] ?>; overflow-y: hidden; width: 715px; margin: 0px auto;">

        <div class="flipbooks_strip" style="width: <?= $att_flipbooks['ancho'] ?>px;">
            <?php foreach ($flipbooks->result() as $row_flipbook): ?>
                <?php 
                    $imagen_anotaciones = $url_resources . "anotaciones-{$row_flipbook->area_id}-{$row_flipbook->nivel}-mini.jpg";
                ?>
                <div class="flipbook_container">
                    <a href="<?=  $link_anotaciones . $row_flipbook->flipbook_id; ?>">
                        <img width="100" src="<?= $imagen_anotaciones ?>" onError="this.src='<?= $img_anotaciones_general ?>'">
                        <!--<span style="position: absolute; bottom: 0px; left: 0px; width: 100px; background: rgba(255, 255, 255, 0.75);"><? $row_flipbook->nombre_flipbook ?></span>-->
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