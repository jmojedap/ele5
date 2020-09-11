<?php $this->load->view('assets/chosen_jquery'); ?>

<?php

    $i = 0;
    
    $icono_resultado = array(
        '<i class="fa fa-times text-danger"></i>',
        '<i class="fa fa-check text-success"></i>'
    );
    
    //Opciones
        $opciones_flipbook[''] = '[ Filtrar por Contenido ]';
        foreach ( $flipbooks->result() as $row_flipbook ) {
            $indice = '0' . $row_flipbook->flipbook_id;
            $opciones_flipbook[$indice] = $this->App_model->nombre_flipbook($row_flipbook->flipbook_id);
        }
        
        $opciones_quices[''] = '[ Filtrar por tema ]';
        foreach( $quices as $quiz ) {
            $opciones_quices['0' . $quiz['id']] = $this->App_model->nombre_tema($quiz['tema_id']);
        }
        
        /*//Subquices
        foreach ( $subquices as $subquiz )
        {
            $opciones_quices['0' . $subquiz['subquiz_id']] = $subquiz['nombre_quiz'];
        }*/
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var grupo_id = <?= $grupo_id ?>;
    var flipbook_id = <?= intval($flipbook_id) ?>;
    var quiz_id = <?= $quiz_id ?>;

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('#campo-flipbook_id').change(function(){
            flipbook_id = $('#campo-flipbook_id').val();
            window.location = url_app + 'grupos/quices/' + grupo_id + '/' + flipbook_id;
        });
        
        $('#campo-quiz_id').change(function(){
            quiz_id = $('#campo-quiz_id').val();
            //alert(flipbook_id);
            window.location = url_app + 'grupos/quices/' + grupo_id + '/' + flipbook_id + '/' + quiz_id;
        });
        
    });
</script>

<div class="row">
    <div class="col col-md-3">
        <div class="mb-2" style="min-height: 400px;">
            <div class="mb-2">
                <?= form_dropdown('flipbook_id', $opciones_flipbook, $flipbook_id, 'id="campo-flipbook_id" class="form-control chosen-select"') ?>
            </div>
            <div class="mb-2">
                <?= form_dropdown('quiz_id', $opciones_quices, $quiz_id, 'id="campo-quiz_id" class="form-control chosen-select"') ?>
            </div>
    
        </div>
    </div>
    <div class="col col-md-9">
        
        <div class="mb-2">
            <div class="row">
                <div class="col-md-12">
                    <?= anchor("grupos/quices_exportar/{$grupo_id}/{$quiz_id}", '<i class="fa fa-file-excel-o"></i> Exportar', 'class="btn btn-success" title="Exportar resultados a MS-Excel" target="_blank"') ?>
                    <?= anchor("quices/resolver/{$quiz_id}", '<i class="fa fa-laptop"></i> Abrir evidencia', 'class="btn btn-secondary" title="Exportar resultados a MS-Excel" target="_blank"') ?>
                </div>
            </div>
        </div>
        
        
        <table class="table bg-white">
            <thead>
                <th>Estudiante</th>
                <th>Estado</th>
                <th>Intentos</th>
                <th>Fecha</th>
                <th>Hace</th>
            </thead>
            <tbody>
                <?php foreach ($estudiantes->result() as $row_estudiante) : ?>
                    <?php
                        //Valores por defecto

                        $estado_quiz = $this->Usuario_model->estado_quiz($row_estudiante->usuario_id, $quiz_id);
                        
                        $fecha = $this->Pcrn->si_nulo($estado_quiz['editado'], '-', $this->Pcrn->fecha_formato($estado_quiz['editado'], 'Y-M-d'));
                        $tiempo_hace = $this->Pcrn->si_nulo($estado_quiz['editado'], '-', $this->Pcrn->tiempo_hace($estado_quiz['editado']));
                        $resultado = $this->Pcrn->si_nulo($estado_quiz['resultado'], 'Sin abrir', $icono_resultado[$estado_quiz['resultado']] );
                        
                        $clase_fila = '';
                            
                        switch ($estado_quiz['resultado']) {
                            case '':
                                $clase_fila = '';
                                break;
                            case 0:
                                $clase_fila = 'table-danger';
                                break;
                            case 1:
                                $clase_fila = 'table-success';
                                break;
                        }
                    ?>
                    <tr>
                        <td><?= anchor("usuarios/quices/{$row_estudiante->usuario_id}/{$flipbook_id}", $row_estudiante->apellidos . ' ' . $row_estudiante->nombre, 'class="" title=""') ?></td>
                        <td class="<?= $clase_fila ?> text-center"><?= $resultado ?></td>
                        <td class="<?= $clase_fila ?>"><?= $estado_quiz['cant_intentos'] ?></td>
                        <td><?= $fecha ?></td>
                        <td><?= $tiempo_hace ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>