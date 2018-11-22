<?php
    $att_id_alfanumerico = array(
        'name' => 'id_alfanumerico',
        'id' => 'id_alfanumerico'
    );

    $att_quiz_id = array(
        'name' => 'quiz_id',
        'id' => 'quiz_id'
    );
    
    $att_tipo_id = array(
        'name' => 'tipo_id',
        'id' => 'tipo_id'
    );
    
    $att_orden = array(
        'name' => 'orden',
        'id' => 'orden'
    );
    
    $att_texto = array(
        'name' => 'texto',
        'id' => 'texto',
        'rows' => 3,
        'style' => 'width: 95%'
    );
    
    $att_opciones = array(
        'name' => 'opciones',
        'id' => 'opciones',
        'rows' => 3,
        'style' => 'width: 95%'
    );
    
    $att_clave = array(
        'name' => 'clave',
        'id' => 'clave',
        'rows' => 3,
        'style' => 'width: 95%'
    );
    
    $att_archivo = array(
        'name' => 'archivo',
        'id' => 'archivo'
    );
    
    $att_x = array(
        'name' => 'x',
        'id' => 'x'
    );
    
    $att_y = array(
        'name' => 'y',
        'id' => 'y'
    );
?>

<script type="text/javascript" src="<?= base_url() ?>js/Math.uuid.js"></script>

<script>
    $(document).ready(function(){
        
        $('#guardar_elemento').click(function(){
            guardar_elemento();
        });
        
        $('.eliminar_elemento').click(function(){
            var id_alfanumerico = $(this).attr('id').substring(9);  //Quitar caracteres de "eliminar_"
            eliminar_elemento(id_alfanumerico);
            $('#fila_' + id_alfanumerico).hide('slow');
        });
        
        $('.editar_elemento').click(function(){
            var id_alfanumerico = $(this).attr('id').substring(7);  //Quitar caracteres de "editar_"
            $('#id_alfanumerico').val(id_alfanumerico);
            $('#quiz_id').focus();
        });
        
        $('#id_alfanumerico').focus(function(){
            $(this).val(Math.uuid(16, 16));
            //$(this).val('hola');
        });
    });
    
    function guardar_elemento(){
        $.ajax({        
            type: 'POST',
            url: '<?= base_url() ?>quices/guardar_elemento',
            data: {
                id_alfanumerico : $('#id_alfanumerico').val(),
                quiz_id : $('#quiz_id').val(),
                tipo_id : $('#tipo_id').val(),
                orden : $('#orden').val(),
                texto : $('#texto').val(),
                opciones : $('#opciones').val(),
                clave : $('#clave').val(),
                archivo : $('#archivo').val(),
                x : $('#x').val(),
                y : $('#y').val()
            }
        });
    }
    
    function eliminar_elemento(id_alfanumerico){
        $.ajax({        
            type: 'POST',
            url: '<?= base_url() ?>quices/eliminar_elemento/' + id_alfanumerico
        });
    }
</script>

<h2>tabla: quiz_detalle</h2>

<table class="tablesorter">
    <thead>
        <th>id</th>
        <th>id_alfanumerico</th>
        <th>quiz_id</th>
        <th>tipo_id</th>
        <th>orden</th>
        <th width="400px">texto</th>
        <th width="400px">opciones</th>
        <th width="400px">clave</th>
        <th>archivo</th>
        <th>x</th>
        <th>y</th>
        <th></th>
        <th></th>
    </thead>
    <tbody>
        <tr>
            <td><span class="button orange" id="guardar_elemento">Guardar</span></td>
            <td><?= form_input($att_id_alfanumerico) ?></td>
            <td><?= form_input($att_quiz_id) ?></td>
            <td><?= form_input($att_tipo_id) ?></td>
            <td><?= form_input($att_orden) ?></td>
            <td><?= form_textarea($att_texto) ?></td>
            <td><?= form_textarea($att_opciones) ?></td>
            <td><?= form_input($att_clave) ?></td>
            <td><?= form_input($att_archivo) ?></td>
            <td><?= form_input($att_x) ?></td>
            <td><?= form_input($att_y) ?></td>
            <td></td>
            <td></td>
        </tr>
        <?php foreach ($elementos->result() as $row_elemento) : ?>
            <tr id="fila_<?= $row_elemento->id_alfanumerico ?>">
                <td><?= $row_elemento->id ?></td>
                <td><?= $row_elemento->id_alfanumerico ?></td>
                <td><?= $row_elemento->quiz_id ?></td>
                <td><?= $row_elemento->tipo_id ?></td>
                <td><?= $row_elemento->orden ?></td>
                <td><?= $row_elemento->texto ?></td>
                <td><?= $row_elemento->detalle ?></td>
                <td><?= $row_elemento->clave ?></td>
                <td><?= $row_elemento->archivo ?></td>
                <td><?= $row_elemento->x ?></td>
                <td><?= $row_elemento->y ?></td>
                <td>
                    <span class="button white small editar_elemento" id="editar_<?= $row_elemento->id_alfanumerico ?>"><i class="fa fa-pencil"></i></span>
                </td>
                <td>
                    <span class="button white small eliminar_elemento" id="eliminar_<?= $row_elemento->id_alfanumerico ?>"><i class="fa fa-trash-o"></i></span>
                </td>
            </tr>
            
        <?php endforeach ?>
    </tbody>
</table>
