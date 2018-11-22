<?php
    //Imágenes
    $carpeta_paginas = RUTA_UPLOADS . 'pf_mini/';
    
    $src_alt = base_url() . RUTA_IMG . 'app/pf_nd_1.png';   //Imagen alternativa

    $att_mini = array(
        'title' =>  'Imagen',
        'class' =>  'pf',
        'width'  => '80px',
        'onError' => "this.src='" . $src_alt . "'", //Imagen alternativa
    );
?>

<div class="row">
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                Contenidos
            </div>
            <div class="panel-body">
                <ul class="nav nav-pills nav-stacked">
                    <?php foreach ($flipbooks->result() as $row_flipbook) : ?>
                        <?php
                            $clase = '';
                            if ( $row_flipbook->flipbook_id == $flipbook_id ) { $clase = 'active'; }
                        ?>
                        <li role="presentation" class="<?= $clase ?>">
                            <?= anchor("usuarios/anotaciones/{$row->id}/{$row_flipbook->flipbook_id}", $row_flipbook->nombre_flipbook) ?>
                        </li>    
                    <?php endforeach ?>
                </ul>
            </div>
            
        </div>
    </div>

    <div class="col-md-9">
        <div class="panel panel-default" style="max-width: 800px">
            <div class="panel-body">
                <table class="tabla-transparente" style="width: 100%">
                    <tbody>
                        <?php foreach ($anotaciones->result() as $row_anotacion): ?>

                            <?php
                                $row_pf = $this->Pcrn->registro_id('pagina_flipbook', $row_anotacion->pagina_id);
                                $att_mini['src'] = "{$carpeta_paginas}{$row_pf->archivo_imagen}";
                            ?>
                        
                            <tr style="border-bottom: 1px solid #eaeaea;">
                                <td style="padding: 10px 10px 10px 0;" class="hidden-xs">
                                    <?= img($att_mini); ?>
                                </td>
                                <td style="vertical-align: top; padding-top: 10px;">
                                    <span class="resaltar"><?= $this->App_model->nombre_tema($row_pf->tema_id) ?></span>
                                    <div class="pull-right">
                                        <span class="suave"><?= $this->Pcrn->fecha_formato($row_anotacion->editado, 'Y-M-d') ?> | Hace <?= $this->Pcrn->tiempo_hace($row_anotacion->editado) ?></span>
                                    </div>

                                    <p style="padding-top: 10px;"><?= $row_anotacion->anotacion ?></p>
                                </td>
                            </tr>


                        <?php endforeach ?>
                        
                    </tbody>
                </table>
                
            </div>
        </div>
        
        
    </div>
</div>

