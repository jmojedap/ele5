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

<h4>Hoja de Respuestas</h4>

<hr/>

<table class="table table-bordered table-condensed bg-blanco">
    <thead>
    <th class="<?= $clases_col['num_pregunta'] ?>">No.</th>
    
    <th class="<?= $clases_col['opcion_1'] ?> text-center">A</th>
    <th class="<?= $clases_col['opcion_2'] ?> text-center">B</th>
    <th class="<?= $clases_col['opcion_3'] ?> text-center">C</th>
    <th class="<?= $clases_col['opcion_4'] ?> text-center">D</th>
    
    <th class="<?= $clases_col['texto_pregunta'] ?>">Pregunta</th>
</thead>



<tbody>
    <?php foreach ($preguntas->result() as $row_pregunta) : ?>
        <?php 
            $num_pregunta++;
            
            //Array opciones por defecto
            $arr_opciones = array(
                1 => '',
                2 => '',
                3 => '',
                4 => ''
            );
            
            //Asignando valor a la correcta
            $arr_opciones[$row_pregunta->respuesta_correcta] = '<i class="fa fa-check-circle"></i>';
        ?>
        <tr>
            <td class="<?= $clases_col['num_pregunta'] ?> text-center" width="25px">
                <?= $num_pregunta ?>
            </td>
            
            <td class="<?= $clases_col['opcion_1'] ?> text-center">
                <?= $arr_opciones[1] ?>
            </td>
            <td class="<?= $clases_col['opcion_2'] ?> text-center">
                <?= $arr_opciones[2] ?>
            </td>
            <td class="<?= $clases_col['opcion_3'] ?> text-center">
                <?= $arr_opciones[3] ?>
            </td>
            <td class="<?= $clases_col['opcion_4'] ?> text-center">
                <?= $arr_opciones[4] ?>
            </td>
            
            <td class="<?= $clases_col['texto_pregunta'] ?>">
                <?= word_limiter($row_pregunta->texto_pregunta, 10) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
</table>