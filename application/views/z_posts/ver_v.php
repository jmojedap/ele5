<?php
    //Resumen
        $peso_total = 0;
        
        foreach ($detalle->result() as $row_detalle) {
            $peso_total += $row_detalle->cantidad * $row_detalle->peso;
        }
        
    $att_cantidad = array(
        'id'     => 'cantidad',
        'name'   => 'cantidad',
        'class'  => 'form-control',
        'value'  => '1',
        'placeholder'   => 'Cantidad',
        'type' => 'number',
        'min' => '1',
        'max' => '10000',
        'step' => '1'
    );
    
    //Opciones productos
        $opciones_producto = $this->App_model->opciones_producto('id > 0');
?>

<script>
    //Variables
        var base_url = '<?= base_url() ?>';
        var pedido_id = <?= $row->id ?>;
        var estado_pedido = <?= $row->estado_pedido ?>;
</script>

<script>
    $(document).ready(function(){
        $('.boton_estado').click(function(){
            estado_pedido = $(this).data('estado');
            act_estado(); 
        });
    });
</script>

<script>
    //Ajax
    function act_estado(){
        $.ajax({        
            type: 'POST',
            url: base_url + 'pedidos/act_estado/' + pedido_id + '/' + estado_pedido,
            success: function(){
                window.location = base_url + 'pedidos/ver/' + pedido_id;
            }
        });
    }
</script>

<div class="box box-info">
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group" role="group">
                    <?php foreach ($estados->result() as $row_estado) : ?>
                        <?php
                            $clase = 'btn-default';
                            if ( $row_estado->id_interno == $row->estado_pedido ) { $clase = 'btn-warning'; }
                        ?>
                        <button type="button" data-estado="<?= $row_estado->id_interno ?>" class="boton_estado btn <?= $clase ?>"><?= $row_estado->item ?></button>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
        
        <hr/>
        
        <div class="div2">
            
            <div class="row">
                <div class="col-md-4">
                    <dl class="dl-horizontal">
                        <dt>Cód. pedido</dt>
                        <dd class="bg-warning"><?= $row->cod_pedido ?></dd>
                        
                        <dt>Cliente</dt>
                        <dd><?= $row->nombre . ' ' . $row->apellidos ?></dd>
                        
                        <dt>Documento</dt>
                        <dd><?= $row->no_documento ?></dd>
                        
                        <dt>E-mail</dt>
                        <dd><?= $row->email ?></dd>
                        
                        <dt>Creado</dt>
                        <dd>
                            <?= $this->Pcrn->fecha_formato($row->creado, 'Y-M-d h:i') ?>
                            - Hace <?= $this->Pcrn->tiempo_hace($row->creado) ?>
                        </dd>
                        
                        <dt>Peso</dt>
                        <dd><?= $row->peso_total ?> kg</dd>
                        
                        <dt>Subtotal productos</dt>
                        <dd>
                            <?= $this->Pcrn->moneda($row->total_productos) ?>
                        </dd>
                        
                        <dt>Subtotal otros</dt>
                        <dd>
                            <?= $this->Pcrn->moneda($row->total_extras) ?>
                        </dd>
                        
                        <dt>Total</dt>
                        <dd class="">
                            <b>
                                <?= $this->Pcrn->moneda($row->valor_total) ?>
                            </b>
                        </dd>
                        
                    </dl>
                </div>
                
                <div class="col-md-8">
                    <dl class="dl-horizontal">
                        <dt>Editado por</dt>
                        <dd>
                            <?= $this->App_model->nombre_usuario($row->editado_usuario_id, 2) ?>
                        </dd>
                        
                        <dt>Editado</dt>
                        <dd>
                            <?= $this->Pcrn->fecha_formato($row->editado, 'Y-M-d h:i') ?>
                            - Hace <?= $this->Pcrn->tiempo_hace($row->editado) ?>
                        </dd>
                        
                        <dt>Factura</dt>
                        <dd>
                            <?= $row->factura ?>
                        </dd>
                        
                        <dt>Guía</dt>
                        <dd>
                            <?= $row->no_guia ?>
                        </dd>
                        
                        
                        <dt>Anotaciones</dt>
                        <dd>
                            <?= $row->notas_admin ?>
                        </dd>

                    </dl>
                    <hr/>
                    <dl class="dl-horizontal">
                        <dt>Ciudad</dt>
                        <dd><?= $row->ciudad ?></dd>
                        
                        <dt>Dirección</dt>
                        <dd><?= $row->direccion ?></dd>
                        
                        <dt>Teléfono</dt>
                        <dd><?= $row->telefono ?></dd>
                        
                        <dt>Celular</dt>
                        <dd><?= $row->celular ?></dd>
                        
                        <dt>Notas</dt>
                        <dd><?= $row->notas ?></dd>
                    </dl>
                </div>
            </div>
            
            
        </div>
        
        <h3>Productos</h3>
        
        <table class="table table-hover table-bordered">
            <thead>
                <th width="45px;" class="warning">ID</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
                <th>Peso (g)</th>
                <th>Peso Total</th>
            </thead>

            <tbody>    
                <tr class="success">
                    <td></td>
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td>
                        <b>
                            <?= $this->Pcrn->moneda($row->total_productos) ?>
                        </b>
                    </td>
                    <td></td>
                    <td><?= $row->peso_total ?> kg</td>
                </tr>
                <?php foreach ($detalle->result() as $row_detalle) : ?>
                    <?php
                        $sum_precio = $row_detalle->cantidad * $row_detalle->precio;
                        $sum_peso = $row_detalle->cantidad * $row_detalle->peso;

                        //Checkbox
                            $att_check = array(
                                'class' =>  'check_registro',
                                'data-id' => $row_detalle->id,
                                'checked' => FALSE
                            );
                    ?>

                    <tr>
                        <td class="warning"><span class="etiqueta primario w1"><?= $row_detalle->id ?></span></td>
                        <td><?= $row_detalle->nombre_producto ?></td>
                        <td><?= $row_detalle->cantidad ?></td>
                        <td><?= $this->Pcrn->moneda($row_detalle->precio) ?></td>
                        <td><?= $this->Pcrn->moneda($sum_precio) ?></td>
                        <td><?= $row_detalle->peso ?> g</td>
                        <td><?= $sum_peso ?> g</td>
                    </tr>
                    <?php endforeach ?>
            </tbody>
        </table>
        
        <h3>Otros</h3>
        <table class="table table-bordered">
            <thead>
                <th>Concepto</th>
                <th>Valor</th>
            </thead>
            <tbody>
                <tr class="success">
                    <td>Total</td>
                    <td>
                        <b>
                            <?= $this->Pcrn->moneda($row->total_extras); ?>
                        </b>
                    </td>
                </tr>
                <?php foreach ($extras->result() as $row_extra) : ?>
                <tr>
                    <td><?= $this->App_model->nombre_item($row_extra->producto_id, 1, 6) ?></td>
                    <td>
                        <span>
                            <?= $this->Pcrn->moneda($row_extra->precio) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>