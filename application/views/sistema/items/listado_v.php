<?php $this->load->view('assets/chosen_jquery'); ?>

<?php 
    $opciones_categorias = array_merge(array('' => "(Seleccione la categoría)"), $arr_categorias);
    
    $titulo_formulario = 'Nuevo elemento';
    $clase_proceso = 'default';
    if ( $item_id > 0 ) 
    {
        $titulo_formulario = 'Editar "' . $row->item . '"';
        $clase_proceso = 'info';
    }
?>

<script>
// Document Ready
//-----------------------------------------------------------------------------
    var base_url = '<?= base_url() ?>';
    var item_id = '<?= $item_id ?>';
    var categoria_id = '<?= $categoria_id ?>';

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function()
    {
        $('#cat').change(function() {
            window.location = '<?= base_url('items/listado') ?>' + '/' + $('#cat').val();
        });
        
        $('.btn_eliminar').click(function(){
            item_id = $(this).data('item_id');
        });
        
        $('#eliminar_elemento').click(function(){
            eliminar();
        });
        
    });
    
// Funciones
//-----------------------------------------------------------------------------
    
    /*
    * AJAX
    * Elimina elemento seleccionado
    */
    function eliminar()
    {
        $.ajax({
            type: 'POST',
            url: base_url + 'items/eliminar',
            data: {
                item_id : item_id,
                categoria_id : categoria_id,
            },
            success: function (rta) {
                console.log(rta);
                if (rta > 0) { ocultar_item(); }
            }
        });
    }
    
    //Oculta la fila del item en la tabla
    function ocultar_item()
    {
        $('#item_' + item_id).hide('fast');
    }
    
</script>

<?php 
    $clases_col['botones'] = '';
?>

<?php if ($vista_menu) { ?>
    <?php $this->load->view($vista_menu); ?>
<?php } ?>

<div class="row">
    <div class="col col-md-6">
        
        <div class="sep1">
            <?= form_dropdown('cat', $opciones_categorias, $categoria_id, 'class="form-control chosen-select" id="cat"') ?>
        </div>
        
        <table class="table table-default bg-blanco">
            <thead>
                <th class="<?= $clases_col['id'] ?>" width="50px">ID</th>
                <th class="<?= $clases_col['id_interno'] ?>" width="50px">Cód.</th>
                <th class="<?= $clases_col['nombre_item'] ?>">
                    Elementos
                    (<?= $items->num_rows() ?>)
                </th>
                <th class="<?= $clases_col['botones'] ?>" width="60px">
                </th>
            </thead>

            <tbody>
                <?php foreach ($items->result() as $row_item) : ?>
                <?php
                    $clase_fila = $this->Pcrn->clase_activa($row_item->id, $item_id, 'info');
                ?>
                    <tr id="item_<?= $row_item->id ?>" class="<?= $clase_fila ?>"> 
                        <td class="<?= $clases_col['id'] ?> warning">
                            <?= $row_item->id ?>
                        </td>
                        <td class="<?= $clases_col['id_interno'] ?>">
                            <?= $row_item->id_interno ?>
                        </td>
                        <td class="<?= $clases_col['nombre_item'] ?>">
                            <?= $row_item->item ?>
                        </td>
                        <td class="<?= $clases_col['botones'] ?>">
                            <a class="a4" href="<?= base_url("items/listado/{$categoria_id}/{$row_item->id}") ?>">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="a4 btn_eliminar" data-item_id="<?= $row_item->id ?>" data-toggle="modal" data-target="#modal_eliminar">
                                <i class="fa fa-times"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="col col-md-6">
        <div class="sep1">
            <a href="<?= base_url("items/listado/{$categoria_id}") ?>" class="btn btn-success">
                <i class="fa fa-plus"></i>
                Nuevo
            </a>
            <button type="button" class="btn btn-default" id="btn_autocompletar">
                Autocompletar
            </button>
        </div>
        
        <div class="panel panel-<?= $clase_proceso ?>">
            <div class="panel-heading">
                <?= $titulo_formulario ?>
            </div>
            <div class="panel-body">
                <?php $this->load->view('sistema/items/formulario_v'); ?>
            </div>
        </div>
        
    </div>
</div>

<?php $this->load->view('comunes/modal_eliminar_simple'); ?>