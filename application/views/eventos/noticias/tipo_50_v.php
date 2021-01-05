<?php
    $row_post = $this->Pcrn->registro_id('post', $row_noticia->referente_id);
?>

<?php if ( ! is_null($row_post) ){ ?>
    <?php if ( $this->session->userdata('usuario_id') == $row_noticia->creador_id ) : ?>                
        <div class="pull-right">
            <div class="a4 eliminar_noticia" data-evento_id="<?= $row_noticia->id ?>">
                <i class="fa fa-times"></i>
            </div>
        </div>
    <?php endif ?>



    <b>
        <?= anchor("usuarios/actividad/{$row_noticia->creador_id}", $this->App_model->nombre_usuario($row_noticia->creador_id, 2), 'class="" title=""') ?>
    </b>

    <br/>
    
    <span class="suave" title="<?= $this->Pcrn->fecha_formato($row_noticia->fecha_inicio, 'Y-M-d') ?>"><?= $this->pml->ago($row_noticia->creado); ?></span>
    
    <p>
        <?= $row_post->contenido ?>
    </p>

    <?php if ( strlen($row_post->texto_1) > 0 ){ ?>
        <?php
            $texto_url = $this->Pcrn->texto_url($row_post->texto_1);
            $link = $this->Pcrn->preparar_url($row_post->texto_1);
        ?>
        <a class="noticia_contenido" href="<?= $link ?>" target="_blank">
            <h4><?= $texto_url ?></h4>
        </a>
    <?php } ?>
<?php } ?>





