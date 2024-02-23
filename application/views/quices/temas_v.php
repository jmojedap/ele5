<?php

    $att_tema = array(
        'id' => 'campo-tema',
        'name' => 'tema',
        'required' => TRUE,
        'autofocus' => TRUE,
        'type' => 'text',
        'class' => 'form-control',
        'value' => '',
        'placeholder' => 'Agregar tema a esta evidencia...',
        'title' => 'Escriba el tema'
    );

?>

<?php $this->load->view('assets/biggora_autocomplete'); ?>

<script>
// Variables
//-----------------------------------------------------------------------------

var base_url = '<?= base_url() ?>';
var quiz_id = <?= $row->id ?>;
var tema_id = 0;

//-----------------------------------------------------------------------------
    
    $(function() {
        function establecer_tema(item) 
        {
            tema_id = item.value;
            agregar_tema();
        }

        $('#campo-tema').typeahead({
            ajax: {
                url: '<?= base_url('app/arr_elementos_ajax/tema') ?>',
                method: 'post',
                triggerLength: 2
            },
            onSelect: establecer_tema
        });
    });
    
    //Ajax
    function agregar_tema()
    {
        $.ajax({        
            type: 'POST',
            url: base_url + 'admin/temas/asignar_quiz',
            data: {
                quiz_id : quiz_id,
                tema_id : tema_id
            },
            success: function(rta){
                window.location = base_url + 'quices/temas/' + quiz_id;
            }
        });
    }
    
    
</script>

<?php $this->load->view('comunes/resultado_proceso_v'); ?>

<div class="container">
    <table class="table bg-white">
        <thead>
            <th class="<?= $clases_col['tema_id'] ?>">ID</th>
            <th class="<?= $clases_col['cod_tema'] ?>">Código</th>
            <th class="<?= $clases_col['nombre_tema'] ?>">Tema</th>
            <th class="<?= $clases_col['botones'] ?>" width="35px"></th>
        </thead>
    
        <tbody>
            <tr class="info">
                <td class="<?= $clases_col['tema_id'] ?>" width="10px"></td>
                <td width="120px"></td>
                <td class="<?= $clases_col['nombre_tema'] ?>">
                    <?= form_input($att_tema) ?>
                </td>
                <td class="<?= $clases_col['botones'] ?>">
                    
                </td>
            </tr>
                
            <?php foreach ($temas->result() as $row_tema) : ?>
                <tr>
                    <td class="<?= $clases_col['tema_id'] ?> warning">
                        <?= $row_tema->id ?>
                    </td>
                    <td class="<?= $clases_col['cod_tema'] ?>">
                        <?= $row_tema->cod_tema ?>
                    </td>
                    <td class="<?= $clases_col['nombre_tema'] ?>">
                        <?= anchor("admin/temas/quices/{$row_tema->id}", $row_tema->nombre_tema) ?>
                    </td>
                    <td class="<?= $clases_col['botones'] ?>">
                        <?= anchor("quices/quitar_tema/{$row->id}/{$row_tema->id}", '<i class="fa fa-times"></i>', 'class="a4"') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
