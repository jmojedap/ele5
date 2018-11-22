<?php if ( $filtros['i'] > 0 ){ ?>
    <tr>
        <td>Institución</td>
        <td>
            <?= anchor("instituciones/grupos/{$filtros['i']}", $this->App_model->nombre_institucion($filtros['i']), 'class="" title=""') ?>
        </td>
    </tr>
<?php } ?>
    
<?php if ( strlen($filtros['n']) > 0 ){ ?>
    <tr>
        <td>Nivel</td>
        <td>
            <?= anchor("instituciones/grupos/{$filtros['n']}", $this->Item_model->nombre(3, $filtros['n']), 'class="" title=""') ?>
        </td>
    </tr>
<?php } ?>
    
<?php if ( strlen($filtros['fa']) > 0 ){ ?>
    <?php
        $arr_intervalos = $this->Busqueda_model->opciones_fecha_atras($filtros['fa']);
    ?>
    <tr>
        <td>Intervalo</td>
        <td>
            <?= $arr_intervalos[$filtros['fa']] ?>
        </td>
    </tr>
<?php } ?>