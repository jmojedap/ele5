<?php 
    $num_pregunta = 0;  //Numerador de preguntas, valor inicial
    
    $enunciados_num_pregunta = array();
    
    //Asociación de preguntas a enunciados
    $i = 0;
    foreach ( $preguntas->result() as $row_pregunta ) 
    {
        $i++;
        if ( strlen($row_pregunta->enunciado_id) > 0 ) 
        {
            $preguntas_enunciado[$row_pregunta->enunciado_id][] = $i;
        }
    }
?>

<table class="table table-bordered sep2">
    <tbody>
        <tr>
            <td width="15%">
                Nombre
            </td>
            <td width="50%">
                
            </td>
            <td width="15%">
                Grupo
            </td>
            <td>
                
            </td>
        </tr>
    </tbody>
</table>

<h4>Preguntas</h4>
<p>Marca la respuesta correcta con una X la casilla <i class="fa fa-square-o"></i></p>

<hr/>

<?php foreach ($preguntas->result() as $row_pregunta) { ?>
    <?php 
        $num_pregunta++;
        
        //Imagen pregunta: pregunta.archivo_imagen
        $src_alt = URL_IMG . "app/img_pregunta_nd.png";   //Imagen alternativa

        $att_img['src'] = RUTA_UPLOADS .  "preguntas/" .$row_pregunta->archivo_imagen;
        $att_img['style'] = 'max-width: 800px; max-height: 180px;';
        $att_img['onError'] = "this.src='" . $src_alt . "'"; //Imagen alternativa
        
        //Datos enunciado
            $enunciado_id = $row_pregunta->enunciado_id;
            if ( strlen($row_pregunta->enunciado_id) > 0 ) 
            {
                $row_enunciado = $this->Pcrn->registro_id('post', $row_pregunta->enunciado_id);
                
                $att_img_enunciado['src'] = URL_UPLOADS . 'enunciados/' . $row_enunciado->texto_2;
                $att_img_enunciado['style'] = 'max-width: 300px; max-height: 300px;';
                //$att_img_enunciado['onError'] = "this.src='" . $src_alt . "'"; //Imagen alternativa
                
            }

    ?>
    
    <?php if ( ! array_key_exists($row_pregunta->enunciado_id, $enunciados_num_pregunta) ) { ?>

        <!-- Mostrar si la pregunta tiene enunciado -->
        <?php if ( strlen($row_pregunta->enunciado_id) > 0 ){?>
            <?php 
                $enunciados_num_pregunta[$row_pregunta->enunciado_id] = $num_pregunta;
            ?>

            <div class="panel panel-default">
                <div class="panel-body text-center text-uppercase">
                    Responda
                    <?= $this->Pcrn->control_plural(count($preguntas_enunciado[$row_pregunta->enunciado_id]), 'la pregunta', 'las preguntas'); ?> 
                    <b><?= implode(', ', $preguntas_enunciado[$row_pregunta->enunciado_id]) ?></b>
                    de acuerdo con la siguiente información     
                </div>
            </div>


            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= $row_enunciado->nombre_post ?>
                </div>
                <div class="panel-body">
                    <?= $row_enunciado->contenido ?>
                    <?php if ( strlen($row_enunciado->texto_2) > 0 ){ ?>
                        <hr/>
                        <div>
                            <div style="margin: 0 auto; max-width: 600px; max-height: 600px;">
                                <?= img($att_img_enunciado) ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

    <?php } else { ?>
        <div class="panel panel-default">
            <div class="panel-body text-center text-uppercase">
                Responda la pregunta <b><?= $num_pregunta ?></b> de acuerdo con información
                mostrada en la pregunta <b><?= $enunciados_num_pregunta[$row_pregunta->enunciado_id] ?><b/>
            </div>
        </div>
    <?php } ?>

    
    <p>    
        <b style="margin-right: 10px;">
            <?= $num_pregunta ?>.
        </b>
        <?= $row_pregunta->texto_pregunta; ?>
    </p>
    
    
    <!-- Mostrar imagen si la pregunta tiene respuestas en imagen -->
    <?php if ( strlen($row_pregunta->archivo_imagen) > 0 ):?>
        <div class="sep1 text-center">
            <div class="thumbnail">
                <?= img($att_img) ?>
            </div>
        </div>
    <?php endif ?>
    
        <ol style="list-style-type: upper-latin">
            <li>
                <i class="fa fa-square-o"></i>
                <?= $row_pregunta->opcion_1 ?>
            </li> 
            <li>
                <i class="fa fa-square-o"></i>
                <?= $row_pregunta->opcion_2 ?>
            </li> 
            <li>
                <i class="fa fa-square-o"></i>
                <?= $row_pregunta->opcion_3 ?>
            </li> 
            <li>
                <i class="fa fa-square-o"></i>
                <?= $row_pregunta->opcion_4 ?>
            </li> 
        </ol>
    <hr/>
<?php } ?>