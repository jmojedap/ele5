<script>
// Variables
//-----------------------------------------------------------------------------
    var base_url = '<?php echo base_url() ?>';
    var pregunta_id = '<?php echo $row->id?>';
    var version_id = '<?php echo $row_version->id?>';

// Document ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('#btn_aprobar').click(function(){
            aprobar_version();
        });

        $('#btn_delete_element').click(function(){
            eliminar_version();
        });
    });

// Funciones
//-----------------------------------------------------------------------------
    function aprobar_version(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'preguntas/approve_version/' + pregunta_id + '/' + version_id,
            success: function(response){
                if (response.status == 1) {
                    window.location = base_url + 'preguntas/detalle/' + pregunta_id;
                }
            }
        });
    }

    function eliminar_version(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'preguntas/delete_version/' + pregunta_id + '/' + version_id,
            success: function(response){
                if (response.status == 1) {
                    window.location = base_url + 'preguntas/detalle/' + pregunta_id;
                }
            }
        });
    }
</script>

<?php
    $advertencia = '<i class="fa fa-exclamation-triangle text-warning"></i>';
?>

<table class="table bg-white">
    <thead>
        <th></th>
        <th>Campo</th>
        <th>Original</th>
        <th>Versión propuesta</th>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <?php if ( $this->session->userdata('rol_id') <= 2 ) { ?>
                    <button class="btn btn-success" id="btn_aprobar">
                        <i class="fa fa-check"></i>
                        Aprobar versión
                    </button>
                <?php } ?>
                <a href="<?php echo base_url("preguntas/version/{$row->id}/editar") ?>" class="btn btn-primary">
                    <i class="fa fa-pencil-alt"></i>
                    Editar
                </a>

                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#delete_modal" title="Descartar esta versión propuesta de la pregunta">
                    <i class="fa fa-trash"></i>
                    Eliminar
                </button>
            </td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->cod_pregunta != $row_version->cod_pregunta ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Código</td>
            <td width="40%"><?php echo $row->cod_pregunta ?></td>
            <td width="40%"><?php echo $row_version->cod_pregunta ?></td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->texto_pregunta != $row_version->texto_pregunta ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Texto pregunta</td>
            <td><?php echo $row->texto_pregunta ?></td>
            <td><?php echo $row_version->texto_pregunta ?></td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->enunciado_2 != $row_version->enunciado_2 ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Enunciado complementario</td>
            <td><?php echo $row->enunciado_2 ?></td>
            <td><?php echo $row_version->enunciado_2 ?></td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->enunciado_id != $row_version->enunciado_id ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Lectura asociada</td>
            <td>
                [ID <?php echo $row->enunciado_id ?>]
                <a href="<?php echo base_url("datos/enunciados_ver/{$row->enunciado_id}") ?>" target="_blank">
                    <?php echo $this->Pcrn->campo_id('post', $row->enunciado_id, 'nombre_post'); ?>
                </a>
            </td>
            <td>
                [ID <?php echo $row_version->enunciado_id ?>]
                <a href="<?php echo base_url("datos/enunciados_ver/{$row_version->enunciado_id}") ?>" target="_blank">
                    <?php echo $this->Pcrn->campo_id('post', $row_version->enunciado_id, 'nombre_post'); ?>
                </a>
            </td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->opcion_1 != $row_version->opcion_1 ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Opción A</td>
            <td><?php echo $row->opcion_1 ?></td>
            <td><?php echo $row_version->opcion_1 ?></td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->opcion_2 != $row_version->opcion_2 ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Opción B</td>
            <td><?php echo $row->opcion_2 ?></td>
            <td><?php echo $row_version->opcion_2 ?></td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->opcion_3 != $row_version->opcion_3 ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Opción C</td>
            <td><?php echo $row->opcion_3 ?></td>
            <td><?php echo $row_version->opcion_3 ?></td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->opcion_4 != $row_version->opcion_4 ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Opción D</td>
            <td><?php echo $row->opcion_4 ?></td>
            <td><?php echo $row_version->opcion_4 ?></td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->nivel != $row_version->nivel ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Nivel</td>
            <td><?php echo $this->Item_model->nombre(3, $row->nivel) ?></td>
            <td><?php echo $this->Item_model->nombre(3, $row_version->nivel) ?></td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->area_id != $row_version->area_id ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Área</td>
            <td><?php echo $this->Item_model->nombre_id($row->area_id) ?></td>
            <td><?php echo $this->Item_model->nombre_id($row_version->area_id) ?></td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->competencia_id != $row_version->competencia_id ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Compentencia</td>
            <td><?php echo $this->Item_model->nombre_id($row->competencia_id) ?></td>
            <td><?php echo $this->Item_model->nombre_id($row_version->competencia_id) ?></td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->componente_id != $row_version->componente_id ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Componente</td>
            <td><?php echo $this->Item_model->nombre_id($row->componente_id) ?></td>
            <td><?php echo $this->Item_model->nombre_id($row_version->componente_id) ?></td>
        </tr>
        <tr>
            <td>
                <?php if ( $row->archivo_imagen != $row_version->archivo_imagen ) { ?>
                    <?php echo $advertencia ?>
                <?php } ?>
            </td>
            <td>Imagen asociada</td>
            <td>
                <?php if ( strlen($row->archivo_imagen) ) { ?>
                    <img class="rounded" src="<?php echo URL_UPLOADS . 'preguntas/' . $row->archivo_imagen ?>" alt="Imagen original de la pregunta" style="width: 100%">
                <?php } ?>
            </td>
            <td>
                <?php if ( strlen($row_version->archivo_imagen) ) { ?>
                    <img class="rounded" src="<?php echo URL_UPLOADS . 'preguntas/' . $row_version->archivo_imagen ?>" alt="Imagen original de la pregunta" style="width: 100%">
                <?php } ?>
            </td>
        </tr>
    </tbody>
</table>

<?php $this->load->view('comunes/bs4/modal_simple_delete_v') ?>