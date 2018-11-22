<?php
    //Formulario
        $att_form = array(
            'class' => 'form',
            'role' => 'form'
        );

        $att_nombre_lugar = array(
            'class' =>  'form-control',
            'name' => 'nombre_lugar',
            'placeholder' => 'Nuevo lugar'
        );
        
        $att_palabras_clave = array(
            'class' =>  'form-control',
            'name' => 'palabras_clave',
            'placeholder' => 'Palabras similares'
        );


        $att_submit = array(
            'class' =>  'btn btn-info btn-flat',
            'value' =>  'Guardar'
        );
?>

<table class="table table-hover bg-blanco" cellspacing="0">
    <thead>
        <tr class="">
            <th width="45px;" class="warning">ID</th>
            <th>Nombre lugar</th>
            <th>Palabras similares</th>
            <th width="50px"></th>
        </tr>
    </thead>
    <tbody>
        <?php if ( $row->tipo_id <= 3 ) { ?>
            <tr class="info">
                <?= form_open("lugares/guardar/{$row->id}", $att_form) ?>
                    <td></td>
                    <td><?= form_input($att_nombre_lugar); ?></td>
                    <td><?= form_input($att_palabras_clave); ?></td>
                    <td><?= form_submit($att_submit) ?></td>
                <?= form_close('') ?>
            </tr>
        <?php } ?>


        <?php foreach ($sublugares->result() as $row_lugar){ ?>
        <?php
            $funcion = 'sublugares';
            //if ( $row_lugar->tipo_id == 4 ) { $funcion = 'fletes'; }

            //Variables
                $nombre_lugar = $row_lugar->nombre_lugar;
                $link_lugar = anchor("lugares/{$funcion}/$row_lugar->id", $nombre_lugar);
                $editable = TRUE;

            //Checkbox
                $att_check['data-id'] = $row_lugar->id;

        ?>
            <tr>
                <td class="warning"><?= $row_lugar->id ?></td>
                <td><?= $link_lugar ?></td>
                <td><?= $row_lugar->palabras_clave ?></td>


                <td>
                    <?php if ( $editable ){ ?>
                        <?= anchor("lugares/editar/edit/{$row_lugar->id}", '<i class="fa fa-pencil"></i>', 'class="a4" title=""') ?>
                    <?php } ?>
                </td>
            </tr>

        <?php } //foreach ?>
    </tbody>
</table>