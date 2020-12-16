<?php $this->load->view('assets/chosen_jquery') ?>

<?php
    $options_level = $this->App_model->opciones_nivel('item_largo');
    //$options_kit = $this->Kit_model->options();
?>

<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="send_form">

                <div class="form-group row">
                    <label for="code" class="col-md-4 col-form-label text-right">Referencia</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-code"
                            name="code"
                            required
                            class="form-control"
                            placeholder="Referencia"
                            title="Referencia"
                            v-model="form_values.code"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-right">Nombre producto</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-name"
                            name="name"
                            required
                            class="form-control"
                            placeholder="Nombre producto"
                            title="Nombre producto"
                            v-model="form_values.name"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="description" class="col-md-4 col-form-label text-right">Descripción</label>
                    <div class="col-md-8">
                        <textarea
                            id="field-description"
                            name="description"
                            required
                            class="form-control"
                            placeholder="Descripción"
                            title="Descripción"
                            v-model="form_values.description"
                            rows="3"
                            ></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="status" class="col-md-4 col-form-label text-right">Estado</label>
                    <div class="col-md-8">
                        <select name="status" v-model="form_values.status" class="form-control" required>
                            <option v-for="(option_status, status_key) in options_status" v-bind:value="status_key">{{ option_status }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="level" class="col-md-4 col-form-label text-right">Nivel</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('level', $options_level, '0', 'class="form-control" v-model="form_values.level"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="stock" class="col-md-4 col-form-label text-right">Cantidad existencias</label>
                    <div class="col-md-8">
                        <input name="stock" type="number" class="form-control" min="0" required v-model="form_values.stock">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="price" class="col-md-4 col-form-label text-right">Precio de venta</label>
                    <div class="col-md-8">
                        <input
                            name="price" id="field-price" type="number" class="form-control"
                            required min="1"
                            v-model="form_values.price" v-on:change="update_dependents"
                        >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="cost" class="col-md-4 col-form-label text-right">Costo</label>
                    <div class="col-md-8">
                        <input
                            name="cost" id="field-cost" type="number" class="form-control"
                            required min="1"
                            title="" placeholder=""
                            v-model="form_values.cost"
                        >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tax_percent" class="col-md-4 col-form-label text-right">% IVA</label>
                    <div class="col-md-8">
                        <input
                            name="tax_percent" id="field-tax_percent" type="number" class="form-control"
                            required min="0" max="50" step="0.01"
                            title="" placeholder=""
                            v-model="form_values.tax_percent" v-on:change="update_dependents"
                        >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="tax" class="col-md-4 col-form-label text-right">Valor IVA</label>
                    <div class="col-md-8">
                        <input
                            name="tax" id="field-tax" type="number" class="form-control"
                            required step="0.01"
                            v-model="form_values.tax" v-on:change="update_dependents"
                        >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="base_prices" class="col-md-4 col-form-label text-right">Precio base</label>
                    <div class="col-md-8">
                        <input
                            name="base_price" id="field-base_price" type="text" class="form-control"
                            required step="0.01"
                            title="" placeholder=""
                            v-model="form_values.base_price" v-on:change="update_dependents"
                        >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-success w120p" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    //Cargar valor en formulario
    var form_values = <?php echo json_encode($row) ?>;
    form_values.level = '0' + '<?= $row->level ?>';
    form_values.kit_id = '0' + '<?= $row->kit_id ?>';
    form_values.status = '0' + '<?= $row->status ?>';
    
    new Vue({
    el: '#app_edit',
        data: {
            form_values: form_values,
            row_id: '<?php echo $row->id ?>',
            options_status: <?= json_encode($options_status) ?>
        },
        methods: {
            send_form: function() {
                axios.post(url_api + 'products/update/' + this.row_id, $('#edit_form').serialize())
                    .then(response => {
                        console.log(response.data.status);
                        if (response.data.status == 1)
                        {
                            toastr['success']('El producto fue actualizado');
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            update_dependents: function(){
                var base_price = form_values.price / ( 1 + form_values.tax_percent/100) ;
                var tax = parseFloat(form_values.price) - base_price;
                form_values.tax = tax.toFixed(2);
                form_values.base_price = base_price.toFixed(2);
            },
        }
    });
</script>