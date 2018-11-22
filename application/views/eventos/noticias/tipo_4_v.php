<?php if ( $this->session->userdata('usuario_id') == $row_noticia->c_usuario_id ) : ?>                
    <div class="pull-right">
        <div class="a4 eliminar_noticia" data-evento_id="<?= $row_noticia->id ?>">
            <i class="fa fa-times"></i>
        </div>
    </div>
<?php endif ?>



<b>
    <?= anchor("usuarios/actividad/{$row_noticia->c_usuario_id}", $this->App_model->nombre_usuario($row_noticia->c_usuario_id, 2), 'class="" title=""') ?>
</b>

<span class="suave">asignó un link</span>
<br/>
<span class="suave" title="<?= $this->Pcrn->fecha_formato($row_noticia->fecha_inicio, 'Y-M-d') ?>"><?= $this->Pcrn->tiempo_hace($row_noticia->creado); ?></span>
<p>
    <?= $row_noticia->contenido ?>
</p>

<?php if ( strlen($row_noticia->url) > 0 ){ ?>
    <?php
        $texto_url = $this->Pcrn->texto_url($row_noticia->url);
        $link = $this->Pcrn->preparar_url($row_noticia->url);
    ?>
    <a class="noticia_contenido" href="<?= $link ?>" target="_blank">
        <h4>
            <i class="fa fa-globe"></i>
            <?= $texto_url ?>
        </h4>
    </a>
<?php } ?>





