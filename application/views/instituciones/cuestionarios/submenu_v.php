<?php
    if ( $this->session->userdata('rol_id') == 5 ){
        //Es profesor
        $niveles_profesor = $this->App_model->niveles_profesor($this->session->userdata('usuario_id'));
    }
?>

<h3>Resultados de cuestionarios de la institución</h3>

<table class="table table-default bg-blanco">
    <thead>
        <th>Cuestionario</th>
        <th>grupos</th>
        <th>áreas</th>
        <th>competencias</th>
        <th>componentes</th>
    </thead>
    <tbody>
        <?php foreach ($cuestionarios->result() as $row_cuestionario) : ?>
            <tr>
                <?php $nivel_cuestionario = $this->Pcrn->campo('cuestionario', "id = {$row_cuestionario->id}", 'nivel'); ?>


                <?php
                    //Formato del menú, para resaltar opción seleccionada
                    $class_menu = array(
                        'resultados_grupo' => 'class="btn btn-default btn-sm"',
                        'resultados_area' => 'class="btn btn-default btn-sm"',
                        'resultados_componente' => 'class="btn btn-default btn-sm"',
                        'resultados_competencia' => 'class="btn btn-default btn-sm"',
                    );

                    if ( $row_cuestionario->id == $cuestionario_id){
                        $class_menu[$this->uri->segment(2)] = 'class="btn btn-primary btn-sm"';
                    }
                ?>



                <?php if ( $this->session->userdata('rol_id') != 5 ) : ?>
                    <td><?= $this->App_model->nombre_cuestionario($row_cuestionario->id, 1); ?></td>
                    <td class="centrado"><?= anchor("instituciones/resultados_grupo/{$institucion_id}/{$row_cuestionario->id}", 'Ver', $class_menu['resultados_grupo']) ?></td>
                    <td class="centrado"><?= anchor("instituciones/resultados_area/{$institucion_id}/{$row_cuestionario->id}", 'Ver', $class_menu['resultados_area'] ) ?></td>
                    <td class="centrado"><?= anchor("instituciones/resultados_competencia/{$institucion_id}/{$row_cuestionario->id}", 'Ver', $class_menu['resultados_competencia'] ) ?></td>                        
                    <td class="centrado"><?= anchor("instituciones/resultados_componente/{$institucion_id}/{$row_cuestionario->id}", 'Ver', $class_menu['resultados_componente'] ) ?></td>
                <?php elseif ( $this->session->userdata('rol_id') == 5 ) : ?>
                    <?php //Si es profesor se verifica que corresponda a sus niveles ?>
                    <?php if ( in_array($nivel_cuestionario, $niveles_profesor) ){ ?>
                        <td><?= $this->App_model->nombre_cuestionario($row_cuestionario->id, 1); ?></td>
                        <td><?= anchor("instituciones/resultados_grupo/{$institucion_id}/{$row_cuestionario->id}", 'Ver', $class_menu['resultados_grupo']) ?></td>
                        <td><?= anchor("instituciones/resultados_area/{$institucion_id}/{$row_cuestionario->id}", 'Ver', $class_menu['resultados_area'] ) ?></td>
                        <td><?= anchor("instituciones/resultados_competencia/{$institucion_id}/{$row_cuestionario->id}", 'Ver', $class_menu['resultados_competencia'] ) ?></td>
                        <td><?= anchor("instituciones/resultados_componente/{$institucion_id}/{$row_cuestionario->id}", 'Ver', $class_menu['resultados_componente'] ) ?></td>
                    <?php } ?>
                <?php endif ?>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>