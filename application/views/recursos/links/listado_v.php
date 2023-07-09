<?php
    $carpeta_iconos = RUTA_IMG . 'flipbook/';
    
    //Gestión de links de exploración y filtros
        $base_link = 'recursos/links/?';

        
        '' . $filtros_get['editado'] = '';

        $this->db->where('categoria_id', 20);
        $iconos_query = $this->db->get('item');
        $iconos = $this->Pcrn->query_to_array($iconos_query, 'slug', 'id');

        $clase_todos['tipo_archivo_id'] = 'a2 w3';
        if ( is_null($filtros['tipo_archivo_id']) ) { 
            $clase_todos['tipo_archivo_id'] .= ' actual'; 
        }

        $clase_todos['area_id'] = 'a2 w3';
        if ( $filtros['area_id'] == 0 ) {
            $clase_todos['area_id'] .= ' actual';
        }
        

        $clase_todos['nivel'] = 'a2 w3';
        if ( $filtros['nivel'] == 0 ) {
            $clase_todos['nivel'] .= ' actual';
        }
        
        if ( $filtros['editado'] != 0 ) {
            '' . $filtros_get['editado'] = "&e={$filtros['editado']}";
        }
        
        //
        $link_sin_tipo = $base_link . $filtros_get['area_id'] . '&' . $filtros_get['nivel'] . '&' . $filtros_get['editado'];
        $link_sin_area = $base_link . $filtros_get['tipo_archivo_id'] . '&' . $filtros_get['nivel'] . '&' . $filtros_get['editado'];
        $link_sin_nivel = $base_link . $filtros_get['tipo_archivo_id'] . '&' . $filtros_get['area_id'] . '&' . $filtros_get['editado'];
    
    
?>

<div class="div2">
    <?= anchor($link_sin_area, "Áreas", 'class="' . $clase_todos['area_id'] . '" title="Todas las áreas"') ?>
    <?php foreach ($areas->result() as $row_area) : ?>
        <?php
            $clase = 'a2 w3';
            if ( $row_area->id == $filtros['area_id'] ) { $clase .= ' actual'; }
        ?>
        <?= anchor($link_sin_area .  "&a={$row_area->id}", $row_area->item_corto, 'class="' . $clase . '" title=""') ?>
    <?php endforeach ?>
</div>

<div class="div2">
    <?= anchor($link_sin_nivel, "Niveles", 'class="' . $clase_todos['nivel'] . '" title="Todos los niveles"') ?>
    <?php foreach ($niveles->result() as $row_nivel) : ?>
        <?php
            $clase = 'a2 w1';
            if ( $row_nivel->id == $filtros['nivel'] ) { $clase .= ' actual'; }
        ?>
        <?= anchor($link_sin_nivel . "&n={$row_nivel->id}", $row_nivel->id, 'class="' . $clase . '" title=""') ?>
    <?php endforeach ?>
</div>

<div class="div3" style="text-align: center;">
    <?= $this->pagination->create_links(); ?>
</div>

<?php if ( $filtros['editado'] != 0 ){ ?>
    <h4 class="alert_success"><?= 'Se cargaron ' . $this->session->userdata('entero') . ' links' ?></h4>
<?php } ?>
    
<table class="table table-default bg-blanco">
    <thead>
        <th width="50px">Link</th>
        <th>Tema</th>
        <th>Nivel</th>
        <th>Usuario</th>
        <th>Editado hace</th>
    </thead>
    <tbody>
        <?php foreach ($resultados->result() as $row_link) : ?>
            <?php
                $att_icono['src'] = "{$carpeta_iconos}{$iconos[$row_link->tipo_archivo_id]}.png";
            ?>
            <tr>
                <td><?= anchor($row_link->url, img($att_icono), 'target="_blank" title=""') ?></td>
                <td><?= anchor("admin/temas/archivos/{$row_link->tema_id}", $row_link->nombre_tema, 'class="" title=""') ?></td>
                <td><?= $row_link->nivel ?></td>
                <td><?= $this->App_model->nombre_usuario($row_link->usuario_id, 2) ?></td>
                <td><?= $this->Pcrn->tiempo_hace($row_link->editado) ?></td>
            </tr>

        <?php endforeach ?>
    </tbody>
</table>
